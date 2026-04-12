<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ReorderRequest;
use App\Models\Additional;
use App\Models\AppSetting;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\StoreSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderRepository extends Repository
{
    public function model()
    {
        return Order::class;
    }


    public function getTopStores()
    {
        $appSetting = AppSetting::first();
        $userLat = request('lat');
        $userLng = request('lng');

        $topStoreIds = DB::table('ratings')
            ->select(
                'store_id',
                DB::raw('AVG(rating) as avg_rating'),

            )
            ->groupBy('store_id')
            ->orderByDesc('avg_rating')
            ->pluck('store_id');

        $topStores = (new StoreRepository())->query()->whereIn('id', $topStoreIds)->limit(8)->get();

        $validStores = collect();

        foreach ($topStores as $store) {
            if ($store->latitude && $store->longitude) {
                $store->distance = $this->getShopDistance(
                    [$userLat, $userLng],
                    [$store->latitude, $store->longitude]
                );
            } else {
                $store->distance = null;
            }

            if ($appSetting && $appSetting->business_based_on === 'commission') {
                if ($store->commission_wallet < $store->commission_due_limit) {
                    $validStores->push($store);
                }
                continue;
            }

            $storeSubscription = StoreSubscription::where('store_id', $store->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($storeSubscription && $storeSubscription->expired_at) {
                $dueDate = Carbon::now()
                    ->diffInDays(Carbon::parse($storeSubscription->expired_at), false);

                if ($dueDate > 0) {
                    $store->due_date = $dueDate;
                    $validStores->push($store);
                }
            }
        }

        return $validStores;
    }

    function getShopDistance($point1, $point2)
    {
        $earthRadius = 6371; // km

        // Force float conversion
        $latFrom = deg2rad((float) $point1[0]);
        $lonFrom = deg2rad((float) $point1[1]);
        $latTo   = deg2rad((float) $point2[0]);
        $lonTo   = deg2rad((float) $point2[1]);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) *
                pow(sin($lonDelta / 2), 2)
        ));

        return $earthRadius * $angle;
    }

    public function getByStatus($status)
    {
        $orders = $this->query()->where('order_status', $status);
        $user = (new UserRepository())->find(auth()->id());
        if ($user->hasRole('store')) {
            $orders = $orders->where('store_id', auth()->user()->store->id);
        }

        return $orders->get();
    }
    public function getBySearch($search)
    {
        $customer = auth()->user()->customer;

        if ($search) {
            if ($search == 'all') {
                $orders = $customer->orders();
            } elseif ($search == 'search') {
                $orders = $this->query()->where('order_code', request('order_no'));
            } else {
                $orders = $this->query()->where('customer_id', $customer->id)->where('order_status', $search);
            }
        } else {
            $orders = $customer->orders();
        }

        return $orders->get();
    }

    public function getByTodays()
    {
        return $this->model()::whereDate('created_at', Carbon::today())->get();
    }

    public function storeByRequest(Request $request, $pick_hour, $delivery_hour): Order
    {


        if ($request->store_id) {
            $store = (new StoreRepository())->findOrFail($request->store_id);


            if (!$request->filled('store_slug')) {
                $request->merge([
                    'store_slug' => $store->slug,
                ]);
            }
        } else {
            $store = (new StoreRepository())
                ->model()
                ->where('slug', $request->store_slug)
                ->firstOrFail();
        }


        $lastOrder = $this->query()->latest('id')->first();

        $customer = auth()->user()->customer;

        $address = $customer->addresses()->where('is_default', 1)->first();

        $getAmount = $this->getAmount($request);
        $coupon = null;
        if (!empty($request->promo_code)) {
            $coupon = Coupon::where('code', $request->promo_code)
                ->where('store_id', $store->id)
                ->first();
        }


        // $paymentType = $request->payment_mode == 'card' ? $request->payment_method : $request->payment_mode;
        $paymentType = $request->payment_type ?? $request->payment_method ?? 'cash';


        $order = $this->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'order_code' => str_pad($lastOrder ? $lastOrder->id + 1 : 1, 6, '0', STR_PAD_LEFT),
            'prefix' => $store->prifix ?? 'MS',
            'coupon_id' => $coupon->id ?? null,
            'pick_date' => $request->pick_date,
            'delivery_date' => $request->delivery_date,
            'pick_hour' => $pick_hour,
            'delivery_hour' => $delivery_hour,
            'delivery_charge' => $getAmount['delivery_charge'],
            'discount'        => $getAmount['discount'],
            'total_amount'    => $getAmount['subtotal'],
            'payable_amount'  => $getAmount['total'],
            'payment_status' => PaymentStatus::PENDING->value,
            // 'payment_type' => $request->payment_method ?? 'cash',
            'payment_type' => $paymentType,
            // 'payment_type' => $paymentType ?? 'cash',
            'order_status' => OrderStatus::PENDING->value,
            'address_id' => $request->address_id ?? $address->id,
            'instruction' => $request->instruction,
        ]);

        foreach ($request->products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }

        return $order;
    }

    public function storeByOrderRequest(ReorderRequest $request, Order $order): Order
    {
        $lastOrder = $this->query()->latest('id')->first();

        $newOrder = $this->create([
            'store_id' => $order->store_id,
            'customer_id' => $order->customer_id,
            'order_code' => str_pad($lastOrder ? $lastOrder->id + 1 : 1, 6, '0', STR_PAD_LEFT),
            'prefix' => 'MV',
            'coupon_id' => $order->coupon_id,
            'pick_date' => $request->pick_date,
            'delivery_date' => $request->delivery_date,
            'pick_hour' => $this->setPickOrDeliveryTime($request->pick_date, $request->pick_hour),
            'delivery_hour' => $this->setPickOrDeliveryTime($request->delivery_date, $request->delivery_hour, 'delivery'),
            'payable_amount' => $order->payable_amount,
            'total_amount' => $order->total_amount,
            'delivery_charge' => $order->delivery_charge,
            'discount' => $order->discount,
            'payment_status' => PaymentStatus::PENDING->value,
            'payment_type' => $order->payment_type,
            'order_status' => OrderStatus::PENDING->value,
            'address_id' => $order->address_id,
            'instruction' => $order->instruction,
        ]);

        foreach ($order->products as $product) {
            $newOrder->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
        }

        return $newOrder;
    }

    public function PosStoreByRequest(Request $request): Order
    {

        // $lastOrder = $this->query()->max('id');
        $lastOrder = DB::table('orders')->orderBy('id', 'desc')->first();
        $store = auth()->user()->store;
        $products = $request->products;
        $totalAmount = $request->total_amount;
        $grandTotal = $request->grand_total;
        $lastOrderId = $lastOrder->id;

        $order = $this->create([
            'store_id' => $store?->id,
            'customer_id' => $request->customer_id ?? null,
            'order_code' => str_pad($lastOrderId + 1, 6, '0', STR_PAD_LEFT),
            'pos_order' => true,
            'prefix' => $store->prifix ?? 'LM',
            'pick_date' => now()->format('Y-m-d'),
            'pick_hour' => now()->format('H:00:00'),
            'discount' => $request->discount,
            'delivery_charge' => $request->delivery_charge,
            'payable_amount' => $grandTotal,
            'total_amount' => $totalAmount,
            'payment_status' => PaymentStatus::PAID->value,
            'payment_type' => $request->payment_id,
            'order_status' => OrderStatus::CONFIRM->value,
            'address_id' => $request->address_id ?? null,
            'instruction' => $request->instruction,
        ]);

        foreach ($products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }

        return $order;
    }

    public function PosUpdateByRequest($request, $order): Order
    {


        $order = Order::findOrFail($order);
        $products = $request->products;

        $order->update([
            'payable_amount' => $request->grand_total,
            'discount' => $request->discount,
            'delivery_charge' => $request->delivery_charge,
            'total_amount' => $request->total_amount,
        ]);

        $productData = [];
        if ($products == null) {
            foreach ($order->products as $product) {
                $productData[$product['id']] = ['quantity' => $product->pivot->quantity];
            }
        } else {
            foreach ($products as $product) {
                $productData[$product['id']] = ['quantity' => $product['quantity']];
            }
        }

        $order->products()->sync($productData);

        return $order;
    }

    private function getAmount(Request $request): array
    {

        $store = (new StoreRepository())
            ->model()
            ->where('slug', $request->store_slug)
            ->firstOrFail();


        $subtotal = 0;
        foreach ($request->products as $item) {
            $product = (new ProductRepository())->find($item['id']);
            $price = $product->discount_price ?: $product->price;
            $subtotal += (float) $item['quantity'] * $price;
        }


        $serviceTotal = 0;
        if ($request->has('additional_service_id')) {
            $serviceTotal = Additional::whereIn(
                'id',
                $request->additional_service_id
            )->sum('price');
        }

        $subtotal += $serviceTotal;


        $discount = 0;
        if (!empty($request->promo_code)) {
            $coupon = Coupon::where('code', $request->promo_code)
                ->where('store_id', $store->id)
                ->first();


            if ($coupon) {
                $discount = $coupon->calculate($subtotal, $coupon);
            }
        }


        $deliveryCharge = $store->delivery_charge ?? 0;
        $total = $subtotal - $discount + $deliveryCharge;

        return [
            'subtotal'        => round($subtotal, 2),
            'discount'        => round($discount, 2),
            'delivery_charge' => round($deliveryCharge, 2),
            'total'           => round($total, 2),
        ];
    }

    public function getSortedByRequest(Request $request)
    {
        $status = $request->status;
        $searchKey = $request->search;

        $orders = $this->query();

        $user = (new UserRepository())->find(auth()->id());
        if ($user->hasRole('store')) {
            $orders = $orders->where('store_id', $user->store->id);
        }

        if ($status) {
            $orders = $orders->where('order_status', $status);
        }

        if ($searchKey) {
            $orders = $orders->where(function ($query) use ($searchKey) {
                $query->orWhere('order_code', 'like', "%{$searchKey}%")
                    ->orWhereHas('customer', function ($customer) use ($searchKey) {
                        $customer->whereHas('user', function ($user) use ($searchKey) {
                            $user->where('first_name', $searchKey)
                                ->orWhere('last_name', $searchKey)
                                ->orWhere('mobile', $searchKey);
                        });
                    })
                    ->orWhere('prefix', 'like', "%{$searchKey}%")
                    ->orWhere('amount', 'like', "%{$searchKey}%")
                    ->orWhere('payment_status', 'like', "%{$searchKey}%")
                    ->orWhere('order_status', 'like', "%{$searchKey}%");
            });
        }

        return $orders->latest()->get();
    }

    public function orderListByStatus($status = null)
    {
        $customer = auth()->user()->customer;
        $orders = $this->query()->where('customer_id', $customer->id);

        if ($status) {
            $orders = $orders->where('order_status', $status);
        }


        return $orders->latest()->get();
    }

    /**
     * Commission split for a delivered order. Call only when transitioning to Delivered for the first time.
     */
    public function creditWalletsForDeliveredOrder(Order $order): void
    {
        $commissionCost = round(($order->payable_amount / 100) * $order->store->commission, 2);
        $storeAmount = $order->payable_amount - $commissionCost;

        (new WalletRepository())->updateCredit($order->store->user->wallet, $storeAmount, "Order Delivered from {$order->store->name}", $order->id, null, $order->store->id);

        $rootUser = (new UserRepository())->query()->role('root')->first();
        (new WalletRepository())->updateCredit($rootUser->wallet, $commissionCost, "Order Delivered from {$order->store->name}", $order->id);
    }

    public function statusUpdateByRequest(Order $order, $status): Order
    {
        $order->update([
            'order_status' => $status,
        ]);

        if ($order->drivers && $status == OrderStatus::DELIVERED->value || $status == OrderStatus::PICKED_UP->value) {
            $order->drivers()->update(['is_completed' => true]);
        }

        return $order;
    }

    public function getRevenueReportByBetweenDate($form, $to)
    {
        $storeId = auth()->user()->store?->id;

        return $this->query()->whereBetween('delivery_date', [$form, $to])
            ->where('order_status', OrderStatus::DELIVERED->value)
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->get();
    }

    public function getRevenueReport()
    {
        $year = now()->format('Y');
        $month = now()->format('m');

        $orders = $this->query()->where('order_status', OrderStatus::DELIVERED->value);
        if (auth()->user()->store) {
            $orders = $orders->where('store_id', auth()->user()->store?->id);
        }
        if (request()->type == 'month') {
            $orders = $orders->whereMonth('delivery_date', $month)
                ->whereYear('delivery_date', $year);
        } elseif (request()->type == 'year') {
            $orders = $orders->whereYear('delivery_date', $year);
        } elseif (request()->type == 'week') {
            $end = now()->format('Y-m-d');
            $start = now()->subWeek()->format('Y-m-d');
            $orders = $orders->whereBetween('delivery_date', [$start, $end]);
        } else {
            $date = now()->format('Y-m-d');
            $orders = $orders->where('delivery_date', $date);
        }

        return $orders->get();
    }

    public function getByDatePickOrDelivery($date, $type = 'picked')
    {
        $orders = $this->model()::query();

        if ($type == 'picked') {
            $orders = $orders->where('pick_date', $date);
        }

        if ($type == 'delivery') {
            $orders = $orders->where('delivery_date', $date);
        }

        return $orders->get();
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function setPickOrDeliveryTime($date, $times, $type = 'picked')
    {
        $times = explode('-', $times);

        foreach ($times as $time) {
            $orders = $this->query();
            if ($type == 'picked') {
                $orders = $orders->where('pick_date', $date)->where('pick_hour', 'LIKE', "%$time%");
            }

            if ($type == 'delivery') {
                $orders = $orders->where('delivery_date', $date)->where('delivery_hour', 'LIKE', "%$time%");
            }

            if ($orders->count() < 2) {
                return sprintf('%02s', $time) . ':' . sprintf('%02s', ($orders->count() * 30)) . ':00';
            }
        }
    }
}
