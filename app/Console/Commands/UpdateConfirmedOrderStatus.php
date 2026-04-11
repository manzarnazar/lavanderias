<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class UpdateConfirmedOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // php artisan orders:update-confirmed
    protected $signature = 'orders:update-confirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update orders with order_status = Order confirmed to Confirm';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('order_status', 'Order confirmed')->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found with status "Order confirmed".');
            return;
        }

        foreach ($orders as $order) {
            $order->order_status = 'Confirm';
            $order->save();
            $this->info("Order ID {$order->id} updated to Confirm.");
        }

        $this->info('All relevant orders have been updated.');
    }
}
