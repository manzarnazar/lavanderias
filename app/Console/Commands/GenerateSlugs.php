<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Store;
use App\Models\Variant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSlugs extends Command
{
    protected $signature = 'slugs:generate';
    protected $description = 'Generate slugs for existing records';

    public function handle()
    {
        $this->generate(Service::class, 'name');
        // $this->generate(Product::class, 'name');
        $this->generate(Store::class, 'name');
        $this->generate(Variant::class, 'name');
        $this->generateOrderSlugs();
    }

    private function generate($model, $column)
    {
        $model::whereNull('slug')->chunk(100, function ($records) use ($model, $column) {
            foreach ($records as $record) {
                // dd($record->$column);
                $record->slug = $model::generateUniqueSlug($record->$column);
                $record->save();
            }
        });
    }

   private function generateOrderSlugs()
{
    Order::whereNull('slug')->chunk(100, function ($orders) {
        foreach ($orders as $order) {

            if (empty($order->order_number)) {
                // fallback – guaranteed unique
                $order->slug = 'order-' . $order->id;
            } else {
                $order->slug = 'order-' . Str::slug($order->order_number);
            }

            // Ensure uniqueness
            $baseSlug = $order->slug;
            $count = 1;

            while (Order::where('slug', $order->slug)->exists()) {
                $order->slug = $baseSlug . '-' . $count++;
            }

            $order->save();
        }
    });
}

}

