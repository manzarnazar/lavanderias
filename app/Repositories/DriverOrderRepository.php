<?php

namespace App\Repositories;

use App\Enums\DriverOrderStatus;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use Illuminate\Http\Request;

class DriverOrderRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public function model()
    {
        return DriverOrder::class;
    }

    public function storeByRequest(Driver $driver, Order $order) : DriverOrder
    {
        $assignFor = DriverOrderStatus::DELIVERED->value;
        // if (($order->order_status->value == DriverOrderStatus::CONFIRM->value) || ($order->order_status->value == DriverOrderStatus::PENDING->value)) {
        //     $assignFor = DriverOrderStatus::PICKED_UP->value;
        // }

        return $this->create([
            'driver_id' => $driver->id,
            'order_id' => $order->id,
            'assign_for' => $assignFor,
            'is_completed' => false
        ]);
    }
}
