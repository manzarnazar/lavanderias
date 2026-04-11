<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;


class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->stripe();
        $this->razorpay();
        $this->paystack();
        $this->paypal();
    }

    private function stripe()
    {
        Media::factory()->create([
            'type' => 'image',
            'name' => 'Stripe',
            'extention' => 'png',
            'src' => 'gateway/logo/Stripe.png',
            'description' => '',
            'path' => 'gateway/'
        ]);

    }

    private function paypal()
    {
        Media::factory()->create([
            'type' => 'image',
            'name' => 'Paypal',
            'extention' => 'png',
            'src' => 'gateway/logo/PayPal.png',
            'description' => '',
            'path' => 'gateway/'
        ]);

    }
    private function razorpay()
    {
        Media::factory()->create([
            'type' => 'image',
            'name' => 'Razorpay',
            'extention' => 'png',
            'src' => 'gateway/logo/Razorpay.png',
            'description' => '',
            'path' => 'gateway/'
        ]);

    }

    private function paystack()
    {
        Media::factory()->create([
            'type' => 'image',
            'name' => 'PayStack',
            'extention' => 'png',
            'src' => 'gateway/logo/PayStack.png',
            'description' => '',
            'path' => 'gateway/'
        ]);

    }
}

