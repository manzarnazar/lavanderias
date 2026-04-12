<?php

namespace App\Http\Controllers\API\Driver;

use App\Enums\DriverOrderStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Events\OrderMailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusUpdateRequest;
use App\Http\Resources\RiderOrderDetailsResource;
use App\Http\Resources\RiderOrderResource;
use App\Models\DriverOrder;
use App\Repositories\DriverOrderRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $AllStatus = array_column(DriverOrderStatus::cases(), 'value');

        $request->validate([
            'order_status' => ['nullable', Rule::in($AllStatus)],
        ]);

        $date = $request->date ? parse($request->date, 'Y-m-d') : null;
        $search = $request->search;
        $isComplated = $request->is_complated;

        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 10;
        $skip = ($page * $perPage) - $perPage;

        $driver = auth()->user()->driver;

        $orders = DriverOrder::where('driver_id', $driver->id)
            ->when($request->order_status, function ($query) use ($request) {
                    $query->where('status', $request->order_status);
            })
            ->when($date, function ($query) use ($date) {
                $query->whereHas('order', function ($order) use ($date) {
                    $order->where('pick_date', $date)->orWhere('delivery_date', $date);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('order', function ($order) use ($search) {
                    $order->where('order_code', 'like', "%{$search}%");
                });
            })
            ->when($isComplated, function ($query) {
                $query->whereHas('order', function ($order) {
                    $order->where('is_completed', true);
                });
            }, function ($query) {
                $query->whereHas('order', function ($order) {
                    $order->where('is_completed', false);
                });
            })
            ->latest();

        return $this->json('Orders list', [
            'total' => $orders->count(),
            'orders' => RiderOrderResource::collection($orders->skip($skip)->take($perPage)->get())
        ]);
    }

    public function show(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $driverOrder = (new DriverOrderRepository())->query()->where('order_id', $request->order_id)->first();

        $nextStatus = $this->getNextStatus($driverOrder->status->value);

        $order = (new OrderRepository())->find($request->order_id);
        return $this->json('Order details', [
            'order' => RiderOrderDetailsResource::make($order),
            'next_status' => $nextStatus
        ]);
    }

    public function statusWiseOrders(Request $request)
    {
    }

    private function getNextStatus($status): string
    {
        return match($status){
            DriverOrderStatus::TO_PICKUP->value => DriverOrderStatus::START_PICKING_UP->value,
            DriverOrderStatus::START_PICKING_UP->value => DriverOrderStatus::PICKED_UP->value,
            DriverOrderStatus::PICKED_UP->value => DriverOrderStatus::DROPED_IN_STORE->value,
            DriverOrderStatus::DROPED_IN_STORE->value => DriverOrderStatus::TO_DELIVER->value,
            DriverOrderStatus::TO_DELIVER->value => DriverOrderStatus::START_DELIVERING->value,
            DriverOrderStatus::START_DELIVERING->value => DriverOrderStatus::DELIVERED->value,
            default => 'Complete'
        };
    }

    /**
     * Keep customers / admin order_status aligned with driver milestones (before Delivered).
     */
    private function orderStatusForDriverMilestone(string $driverStatus): ?string
    {
        return match ($driverStatus) {
            DriverOrderStatus::PICKED_UP->value => OrderStatus::PICKED_UP->value,
            DriverOrderStatus::DROPED_IN_STORE->value,
            DriverOrderStatus::TO_DELIVER->value => OrderStatus::PROCESSING->value,
            DriverOrderStatus::START_DELIVERING->value => OrderStatus::ON_GOING->value,
            default => null,
        };
    }

    public function statusUpdate(StatusUpdateRequest $request)
    {
        $driverOrder = (new DriverOrderRepository())->query()->where('order_id', $request->order_id)->first();
        $orderRepository = new OrderRepository();

        $nextStatus = 'Complete';
        if ($driverOrder->is_completed == false) {
            $current = $this->getNextStatus($driverOrder->status->value);

            $driverOrder->update([
                'status' => $current,
            ]);

            $nextStatus = $this->getNextStatus($current);

            if ($current !== DriverOrderStatus::DELIVERED->value) {
                $syncStatus = $this->orderStatusForDriverMilestone($current);
                if ($syncStatus !== null) {
                    $driverOrder->order->update(['order_status' => $syncStatus]);
                }
            }

            if ($current == DriverOrderStatus::DELIVERED->value) {
                $driverOrder->update(['is_completed' => true]);

                $order = $driverOrder->order->fresh();
                $previousStatus = $order->order_status->value;

                $updates = ['order_status' => OrderStatus::DELIVERED->value];
                if ($order->payment_type === PaymentGateway::CASH->value) {
                    $updates['payment_status'] = PaymentStatus::PAID->value;
                }
                $order->update($updates);

                if ($previousStatus !== OrderStatus::DELIVERED->value) {
                    $order = $order->fresh();
                    $orderRepository->creditWalletsForDeliveredOrder($order);

                    if ($order->customer->devices->count()) {
                        $devices = $order->customer->devices;
                        $statusLabel = OrderStatus::DELIVERED->value;
                        $message = "Hello {$order->customer->name}. Your order status is {$statusLabel}. OrderID: {$order->prefix}{$order->order_code}";
                        $tokens = $devices->pluck('key')->toArray();
                        $title = 'Order Status Update';
                        (new NotificationServices())->sendNotification($message, $tokens, $title);
                        (new NotificationRepository())->storeByRequest($order->customer->id, $message, $title);
                    }

                    OrderMailEvent::dispatch($order);
                }
            }
        }

        $driverOrder->load('order');

        return $this->json('Order successfully!', [
            'order' => RiderOrderDetailsResource::make($driverOrder->order->fresh()),
            'next_status' => $nextStatus,
        ]);
    }
}
