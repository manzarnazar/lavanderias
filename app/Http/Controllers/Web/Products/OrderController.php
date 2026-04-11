<?php

namespace App\Http\Controllers\Web\Products;

use App\Enums\OrderStatus;
use App\Events\OrderMailEvent;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Customer;
use App\Models\Order;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\DriverRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\NotificationServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{
    private $orderRepo;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepo = $orderRepository;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepo->getSortedByRequest($request);
        $orderStatus = OrderStatus::cases();

        return view('orders.index', compact('orders', 'orderStatus'));
    }


    public function show($orderID)
    {
        $user = auth()->user();


        $query = Order::withoutGlobalScopes()->where('id', $orderID);


        if (!$user->hasRole('root')) {
            $query->where('store_id', $user->store?->id);
        }

        $order = $query->first();

        if (!$order) {
            abort(403, 'You are not authorized to view this order.');
        }

        $quantity = $order->products->sum('pivot.quantity');
        $drivers = (new DriverRepository())->getAll();
        $orderStatus = OrderStatus::cases();

        return view('orders.show', compact('order', 'quantity', 'drivers', 'orderStatus'));
    }

    public function edit($order)
    {
        $order = Order::findOrFail($order);
        $user = auth()->user();
        $services = $user->store?->services;
        $customers = Customer::all();
        $settings = AppSetting::all();
        $currency = $settings[0]->currency;

        return view('orders.edit', compact('order', 'services', 'customers', 'currency'));
    }

    public function statusUpdate($orderID)
    {
        $status = request('status');

        $order = Order::withoutGlobalScopes()->where('id', $orderID)->first();

        if (!in_array($status, array_column(OrderStatus::cases(), 'value'))) {
            return back()->with('error', 'Invalid status');
        }
        $order = $this->orderRepo->StatusUpdateByRequest($order, $status);

        if ($order->customer?->devices?->count() && $order->customer->user->order_update_notify) {

            $devices = $order->customer?->devices;
            $message = "Hello {$order->customer->name}. Your order status is {$status}. OrderID: {$order->prefix}{$order->order_code}";

            $tokens = $devices->pluck('key')->toArray();
            $title = 'Order Status Update';
            (new NotificationServices())->sendNotification($message, $tokens, $title);

            (new NotificationRepository())->storeByRequest($order->customer->id, $message, $title);
        }

        if ($order->order_status->value == OrderStatus::DELIVERED->value) {
            $commissionAmount = round(($order->payable_amount / 100) * $order->store->commission, 2);
            $storeAmount = $order->payable_amount - $commissionAmount;

            // 1️ Store user wallet (existing)
            (new WalletRepository())->updateCredit(
                $order->store->user->wallet,
                $storeAmount,
                "Order Delivered from {$order->store->name}",
                $order->id,
                null,
                $order->store->id
            );

            // 2️ Root wallet (existing)
            $rootUser = (new UserRepository())->query()->role('root')->first();
            (new WalletRepository())->updateCredit(
                $rootUser->wallet,
                $commissionAmount,
                "Order Delivered from {$order->store->name}",
                $order->id
            );

            // 3️ Store commission_wallet (new)
            $appSetting = AppSetting::first();
            if ($appSetting?->business_based_on == 'commission') {

                $order->store->increment('commission_wallet', $commissionAmount);

                //  VERY IMPORTANT: reload store
                $store = $order->store->fresh();

                //  Commission due lock

            }
            if ($appSetting?->business_based_on == 'commission' && $appSetting?->is_commission_due == 1) {
                if (
                    $store->commission_due_limit > 0 &&
                    $store->commission_wallet >= $store->commission_due_limit
                ) {
                    $store->subscriptions()
                        ->where('status', 1)
                        ->update(['status' => 0]);
                }
            }
        }


        OrderMailEvent::dispatch($order);

        return back()->with('success', 'Status updated successfully');
    }

    public function orderPaid(Order $order)
    {
        $order->update([
            'payment_status' => config('enums.payment_status.paid'),
        ]);

        return back()->with('success', 'Order payment paid successfully');
    }

    public function printLabels(Order $order)
    {
        $productLabels = collect([]);
        $t = 1;
        foreach ($order->products as $key => $product) {
            for ($i = 0; $i < $product->pivot->quantity; $i++) {
                $productLabels[] = [
                    'name' => $order->customer->user->name,
                    'code' => $order->prefix . $order->order_code,
                    'date' => Carbon::parse($order->delivery_at)->format('M d, Y'),
                    'title' => $product->name,
                    'label' => $t . '/' . \request('quantity'),
                ];
                $t++;
            }
        }

        $labels = [];
        $i = 0;
        $r = 0;

        foreach ($productLabels as $key => $label) {
            if ($key + 1 == 1 || $key + 1 == $i) {
                $labels[$r] = [];
                $i = $key + 1 == 1 ? $i + 4 : $i + 3;
                $r++;
            }
            $labels[$r - 1][] = $label;
        }

        $pdf = PDF::loadView('pdf.generate-label', compact('labels'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('labels_' . now()->format('H-i-s') . '.pdf');
    }

    public function printInvioce($orderID)
    {
        $order = Order::withoutGlobalScopes()->where('id', $orderID)->first();

        $quantity = $order->products->sum('pivot.quantity');

        $invoice = auth()->user()->invoice;
        $store = $order->store;
        $appSetting = AppSetting::first();

        $appSetting = AppSetting::first();
        if ($invoice?->type == 'pos') {
            return view('pdf.posIvoice', compact('quantity', 'order', 'store', 'appSetting'));
        }

        $invoiceName = $invoice?->invoice_name ?? 'invoice1';

        $pdf = PDF::loadView('pdf.' . $invoiceName, compact('order', 'quantity', 'store', 'appSetting'))
            ->setPaper('a4', 'portrait');

        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isPhpEnabled', true);

        $pdf->getDomPDF()->getOptions()->set('font-family', 'Noto Sans Bengali');

        return $pdf->stream($order->prefix . $order->order_code . ' - invioce.pdf');
    }

    public function markPaid(Request $request, Order $order)
    {
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $order->payment_status = 'Paid';
        $order->save();

        return response()->json(['success' => true, 'order' => $order]);
    }

    public function repayment($id)
    {
        $order = Order::findOrFail($id);

        // Only allow if payment pending
        if ($order->payment_status !== 'Pending') {
            return redirect()->back()->with('error', 'Payment already completed.');
        }

        //  Same payment route like checkout
        $paymentUrl = route('pos.payment.api', [
            'order' => $order->id,
            'gateway' => $order->payment_type,
            'system' => 'website'
        ]);

        return redirect($paymentUrl);
    }
}
