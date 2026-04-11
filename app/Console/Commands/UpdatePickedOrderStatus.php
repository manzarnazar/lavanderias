<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class UpdatePickedOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-picked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update orders with order_status = piked to picked_up';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('order_status', 'Picked your order')->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found with status "Picked your order".');
            return;
        }

        foreach ($orders as $order) {
            $order->order_status = 'Picked up';
            $order->save();
            $this->info("Order ID {$order->id} updated to Picked up.");
        }

        $this->info('All relevant orders have been updated.');
    }
}
