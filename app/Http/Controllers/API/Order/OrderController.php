<?php

namespace App\Http\Controllers\API\Order;

use App\Enums\OrderStatus;
use App\Events\OrderMailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ScheduleResource;
use App\Models\AdminDeviceKey;
use App\Models\AppSetting;
use App\Models\DriverOrder;
use App\Models\Notification;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Services\NotificationServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Repositories\TransactionRepository;
use App\Models\PaymentGateway;
use App\Repositories\StoreRepository;
use App\Services\StripePaymentService;

class OrderController extends Controller
{
    public function index()
    {
        // $status = config('enums.order_status.' . request('status'));
        $status = request('status');

        $orders = (new OrderRepository())->orderListByStatus($status)->load('rating');

        return $this->json('customer order list', [
            'orders' => OrderResource::collection($orders),
        ]);
    }


    public function store(OrderRequest $request)
    {
        $appSetting = AppSetting::first();
        $maxCompletionTime = $appSetting->pick_to_delivery_gap;
        $pickDate = $request->input('pick_date');
        $deliveryDate = $request->input('delivery_date');
        $pickDate = \Carbon\Carbon::parse($pickDate);
        $deliveryDate = \Carbon\Carbon::parse($deliveryDate);
        $actualDistance = $pickDate->diffInDays($deliveryDate);
        $expectedDistance = $maxCompletionTime;

        if ($actualDistance <= $expectedDistance) {
            $newDeliveryDate = $pickDate->addDays($expectedDistance);
            $newDeliveryDate->addDay();

            $deliveryDate = $newDeliveryDate->toDateString();
            return response()->json([
                'message' => 'You should select the delivery date',
                'adjusted_delivery_date' => $deliveryDate,

            ], 201);
        }

        $availablePickTime = $this->checkPickTime($request->pick_date, $request->pick_hour, $request->store_id);
        $availableDeliveryTime = $this->checkDeliveryTime($request->delivery_date, $request->delivery_hour,  $request->store_id);

        if (empty($availablePickTime['free_times'] ?? []) || empty($availableDeliveryTime['free_times'] ?? [])) {
            return response()->json([
                'message' => 'No available pick or delivery time. Please select another schedule.'
            ], 422); // 422 Unprocessable Entity
        }


        if ($availablePickTime && $availableDeliveryTime) {

            $order = (new OrderRepository())->storeByRequest($request, $availablePickTime['free_times'][0], $availableDeliveryTime['free_times'][0]);
            $transaction = (new TransactionRepository())->storeForOrder($order);

            if ($request->has('additional_service_id')) {
                $order->additionals()->sync($request->additional_service_id);
            }

            $quantity = $order->products->sum('pivot.quantity');
            $store = $order->store;
            $filePath = 'pdf/order' . $order->id . $order->prefix . $order->order_code . rand(10000, 99999) . '.pdf';
            $invoiceName = $store->user?->invoice?->invoice_name ?? 'invoice1';
            $appSetting = AppSetting::first();
            $pdf = PDF::loadView('pdf.' . $invoiceName, compact('order', 'quantity', 'store', 'appSetting'));

            Storage::put($filePath, $pdf->output());

            $order->update([
                'invoice_path' => $filePath,
            ]);

            $deviceKeys = AdminDeviceKey::all();

            $message = "Hello,\r" . 'New order added from ' . $order->customer->name . ".\r" . "Total amount :   $order->total_amount \r" . 'Pick Date: ' . Carbon::parse($order->pick_date)->format('d F Y') . ' - ' . $order->getTime($order->pick_hour) . "\r" . 'Delivery Date: ' . Carbon::parse($order->delivery_date)->format('d F Y') . ' - ' . $order->getTime($order->delivery_hour);

            $keys = $deviceKeys->pluck('key')->toArray();
            $title = 'New Order Added';

            (new NotificationServices())->sendNotification($message, $keys, $title);

            (new NotificationRepository())->storeByRequest($order->customer->id, $message, $title);

            OrderMailEvent::dispatch($order);


            if ($order->payment_type != 'cash') {
                $paymentUrl = route('pos.payment.api', ['order' => $order->id, 'gateway' => $order->payment_type, 'system' => $request->system_type ?? '']);
                return $this->json('Order success', [
                    'message' => 'Order is added successfully',  // Success message
                    'payment_url' => $paymentUrl,              // Payment URL
                    'payment_type' => $order->payment_type,    // Payment type
                    'order' => new OrderResource($order),      // Order data
                ]);
            } else {
                return $this->json('order is added successfully', [
                    'order' => new OrderResource($order),
                ]);
            }
        }
    }


    public function update(Request $request, $order_id)
    {

        $order = DriverOrder::where('order_id', $order_id)->first();

        if (!$order) {
            $order = (new OrderRepository())->PosUpdateByRequest($request, $order_id);
            return $this->json('Order is updated successfully', [
                'order' => new OrderResource($order),
            ]);
        } elseif ($order->status->value === 'To-Pickup') {
            return $this->json('Order already picked up');
        }
    }

    public function show($id)
    {
        $customer = auth()->user()->customer;
        $order = (new OrderRepository())->findById($id);
        $review = $order->rating()
            ->where('customer_id', $customer->id)
            ->first();
        try {
            return $this->json('order details', [
                'order' => new OrderResource($order),
                'review' => $review,
            ]);
        } catch (Exception $e) {
            return $this->json('Sorry, Order not found');
        }
    }

    public function cancle($id)
    {
        $order = (new OrderRepository())->findById($id);
        try {
            if ($order->order_status->value != OrderStatus::PENDING->value) {
                return $this->json('Sorry, order cancle is not possible', [], Response::HTTP_BAD_REQUEST);
            }
            $order->update([
                'order_status' => OrderStatus::CANCELLED->value,
            ]);

            return $this->json('Order cancle successfully', [
                'order' => OrderResource::make($order),
            ]);
        } catch (\Throwable $th) {
            return $this->json('Sorry, Order not found', [], Response::HTTP_BAD_REQUEST);
        }
    }


    public function reorder(ReorderRequest $request)
    {
        $oldOrder = (new OrderRepository())->find($request->order_id);

        if ($oldOrder->order_status->value != OrderStatus::DELIVERED->value) {
            return $this->json('Sorry, Order is not Delivered', [], Response::HTTP_BAD_REQUEST);
        }


        if (empty($request->payment_type)) {
            return $this->json('Payment type is required for reorder', [], Response::HTTP_BAD_REQUEST);
        }

        $appSetting = AppSetting::first();
        $maxCompletionTime = $appSetting->pick_to_delivery_gap;

        $pickDate = Carbon::parse($request->pick_date);
        $deliveryDate = Carbon::parse($request->delivery_date);

        $actualDistance = $pickDate->diffInDays($deliveryDate);
        $expectedDistance = $maxCompletionTime;

        if ($actualDistance <= $expectedDistance) {
            $newDeliveryDate = $pickDate->addDays($expectedDistance)->addDay();

            return response()->json([
                'message' => 'You should select the delivery date',
                'adjusted_delivery_date' => $newDeliveryDate->toDateString(),
            ], 201);
        }

        $availablePickTime = $this->checkPickTime(
            $request->pick_date,
            $request->pick_hour,
            $oldOrder->store_id
        );

        $availableDeliveryTime = $this->checkDeliveryTime(
            $request->delivery_date,
            $request->delivery_hour,
            $oldOrder->store_id
        );

        if (
            empty($availablePickTime['free_times'] ?? []) ||
            empty($availableDeliveryTime['free_times'] ?? [])
        ) {
            return response()->json([
                'message' => 'No available pick or delivery time. Please select another schedule.'
            ], 422);
        }


        $newOrder = (new OrderRepository())->storeByOrderRequest(
            $request,
            $oldOrder,
            $availablePickTime['free_times'][0],
            $availableDeliveryTime['free_times'][0]
        );

        
        $newOrder->payment_type = $request->payment_type;
        $newOrder->save();

        $transaction = (new TransactionRepository())->storeForOrder($newOrder);

        if ($newOrder->payment_type != 'cash') {
            $paymentUrl = route('pos.payment.api', [
                'order' => $newOrder->id,
                'gateway' => $newOrder->payment_type,
                'system' => $request->system_type ?? ''
            ]);

            return $this->json('Order success', [
                'message' => 'Order is added successfully',
                'payment_url' => $paymentUrl,
                'payment_type' => $newOrder->payment_type,
                'order' => new OrderResource($newOrder),
            ]);
        }

        return $this->json('Order added successfully', [
            'order' => new OrderResource($newOrder),
        ]);
    }


    private function checkPickTime($date, $slot, $storeId)
    {
        $store = (new StoreRepository())->find($storeId);
        $freeTimes = $this->checkAvailableTimes($date, $store, $slot, 'pickup');

        return [
            'available' => count($freeTimes) > 0,
            'free_times' => $freeTimes
        ];
    }
    private function checkDeliveryTime($date, $slot, $storeId)
    {
        $store = (new StoreRepository())->find($storeId);
        $freeTimes = $this->checkAvailableTimes($date, $store, $slot, 'delivery');

        return [
            'available' => count($freeTimes) > 0,
            'free_times' => $freeTimes
        ];
    }
    public function checkAvailableTimes($date, $store, $slot, $type)
    {
        $day = Carbon::parse($date)->format('l');

        $schedule = $store->schedules()
            ->where('is_active', true)
            ->where('day', $day)
            ->where('type', $type)
            ->first();

        if (!$schedule) {
            return [
                'available' => false,
                'free_times' => []
            ];
        }

        [$startHour, $endHour] = explode('-', $slot);
        $startHour = (int)$startHour;
        $endHour   = (int)$endHour;

        $slotDuration = ($endHour - $startHour + 1) * 60;

        $interval = $slotDuration / $schedule->per_hour;
        if ($type == 'pickup') {
            $orders = $store->orders()
                ->where('pick_date', Carbon::parse($date)->format('Y-m-d'))
                ->get();
        } else {
            $orders = $store->orders()
                ->where('delivery_date', Carbon::parse($date)->format('Y-m-d'))
                ->get();
        }


        $freeTimes = [];

        for ($m = 0; $m < $slotDuration; $m += $interval) {

            $hour = $startHour + floor($m / 60);
            $minute = $m % 60;

            $time = sprintf('%02d:%02d:00', $hour, $minute);

            $isTaken = false;
            $slotStart = Carbon::createFromTime($hour, $minute, 0);
            $slotEnd = $slotStart->copy()->addMinutes($interval - 1);


            foreach ($orders as $order) {
                $hour = $type == 'pickup' ? $order->pick_hour : $order->delivery_hour;
                if (Carbon::parse($hour)->between($slotStart, $slotEnd)) {
                    $isTaken = true;
                    break;
                }
            }

            if (!$isTaken) {
                $freeTimes[] = $time;
            }
        }

        return $freeTimes;
    }

    public function pickSchedule($store, $date)
    {
        $store = Store::find($store);

        if (! $store) {
            return $this->json('Store not found', [], 200);
        }

        $appSetting = AppSetting::first();
        $maxCompletionTime = $appSetting->pick_to_delivery_gap;

        //  FIRST convert to collection
        $hours = collect(
            $this->getAvailableTimes($store, $date, 'pickup')
        );

        //  THEN check isEmpty
        if ($hours->isEmpty()) {
            return $this->json('Sorry, Our service is not available', [
                'schedules' => [],
            ]);
        }

        return $this->json('picked schedules', [
            'completionTime' => $maxCompletionTime ?? 1,
            'schedules' => ScheduleResource::collection($hours),
        ]);
    }



    public function deliverySchedule(Store $store, $date)
    {

        $hours = $this->getAvailableTimes($store, $date, 'delivery');
        if ($hours->isEmpty()) {
            return $this->json('Sorry, Our service is not abailable', [
                'schedules' => [],
            ]);
        }

        return $this->json('Delivery scheduls', [
            'schedules' => ScheduleResource::collection($hours),
        ]);
    }

    private function getAvailableTimes($store, $date, $type)
    {
        $day = Carbon::parse($date)->format('l');
        $schedule = $store->schedules()
            ->where('is_active', true)
            ->where('day', $day)
            ->where('type', $type)
            ->first();

        $today = ($type === 'pickup') ? date('Y-m-d') : now()->addDay()->format('Y-m-d');

        if (!$schedule || $date < $today) {
            return [];
        }

        $i = ($type === 'pickup' && $today == Carbon::parse($date)->format('Y-m-d')) ? date('H') + 1 : $schedule->start_time;

        $typeSystem = $type === 'pickup' ? 'picked' : 'delivery';
        $orders = (new OrderRepository())->getByDatePickOrDelivery($date, $typeSystem);

        $hours = collect([]);

        for ($i; $i < ($schedule->end_time - 1); $i += 2) {
            $per = 0;
            foreach ($orders as $order) {
                $hourDate = $type === 'pickup' ? $order->pick_hour : $order->delivery_hour;

                $hour = Carbon::parse($hourDate)->format('H');
                // $hour = Carbon::parse($order->pick_hour)->format('H');
                if ($i == $hour || $i + 1 == $hour) {
                    // if ($i == $hour) {
                    $per++;
                }
            }
            if ($per < $schedule->per_hour) {
                // if ($per < ($schedule->per_hour * 2)) {
                $hours[] = [
                    'hour' => (string) $i . '-' . (string) ($i + 1),
                    'title' => sprintf('%02s', $i) . ':00' . ' - ' . sprintf('%02s', $i + 1) . ':59',
                ];
            }
        }

        return $hours;
    }
}
