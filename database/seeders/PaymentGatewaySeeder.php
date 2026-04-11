<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $media = Media::factory()->create();
        // PaymentGateway::truncate();
        PaymentGateway::query()->delete();
        $paymentMethods = [
            [
                'title'             => 'Stripe',
                'name'              => 'stripe',
                'config'            => json_encode([
                    'secret_key'    => 'sk_test_AC8LYQ8cVN0RNGdhZ7G02zWe00lYKYw7LR',
                    'published_key' => 'pk_test_2Iu9vNpu2ROjYOb9KHDBa3Hb00KSavaClK',
                ]),
                'media_id'          => 1,
                'mode'              => 'test',
                'alias'             => 'stripe',
                'is_active'         => true,
            ],

            [
                'title'             => 'Razorpay',
                'name'              => 'razorpay',
                'config'            => json_encode([
                    'key'           => 'rzp_test_k23Mr4BskGqpBu',
                    'secret'        => 'LTrXh7U5xWeZoAHcqdhemFkg',
                ]),
                'media_id'          => 2,
                'mode'              => 'test',
                'alias'             => 'razorpay',
                'is_active'         => true,
            ],
            [
                'title'             => 'Paystack',
                'name'              => 'paystack',
                'config'            => json_encode([
                    'public_key'    => 'pk_test_0c871ddaa80aafd5b64f14390e0745a6c3c274bc',
                    'secret_key'    => 'sk_test_03c7e6762cf1772676272d4677e21e60323610aa',
                    'machant_email' => '',
                ]),
                'media_id'          => 3,
                'mode'              => 'test',
                'alias'             => 'paystack',
                'is_active'         => true,
            ],

            // [
            //     'title'             => 'OrangePay',
            //     'name'              => 'orangepay',
            //     'config'            => json_encode([
            //         'client_id'     => '4d2ceeaa-8ceb-4841-a3a9-4f8a05702092',
            //         'client_secret' => 'bd912517-9db0-4770-9806-61b28414f369',
            //         'merchant_code' => 'bd912517-9db0-4770-9806',
            //     ]),
            //     'media_id'          => 4,
            //     'mode'              => 'test',
            //     'alias'             => 'orangepay',
            //     'is_active'         => true,
            // ],

            [
                'title'             => 'PayFast',
                'name'              => 'payfast',
                'config'            => json_encode([
                    'merchant_id'   => '10036184',
                    'merchant_key'  => 'iz2owp36ngf2n',
                ]),
                'media_id'          => 4,
                'mode'              => 'test',
                'alias'             => 'payfast',
                'is_active'         => true,
            ],
        ];

        PaymentGateway::insert($paymentMethods);
    }
}
