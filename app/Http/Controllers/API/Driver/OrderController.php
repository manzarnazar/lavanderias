<?php

namespace App\Http\Controllers\API\Driver;

use App\Enums\DriverOrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusUpdateRequest;
use App\Http\Resources\RiderOrderDetailsResource;
use App\Http\Resources\RiderOrderResource;
use App\Models\DriverOrder;
use App\Repositories\DriverOrderRepository;
use App\Repositories\OrderRepository;
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

    public function statusUpdate(StatusUpdateRequest $request)
    {
        $driverOrder = (new DriverOrderRepository())->query()->where('order_id', $request->order_id)->first();

        $nextStatus = 'Complete';
        if($driverOrder->is_completed == false){
            $current = $this->getNextStatus($driverOrder->status->value);

            $driverOrder->update([
                'status' => $current
            ]);

            $nextStatus = $this->getNextStatus($current);

            if($current == DriverOrderStatus::DELIVERED->value){
                $driverOrder->update(['is_completed' => true]);
            }
        }

        // OrderMailEvent::dispatch($order);

        return $this->json("Order successfully!", [
            'order' => RiderOrderDetailsResource::make($driverOrder->order),
            'next_status' => $nextStatus
        ]);
    }
}
