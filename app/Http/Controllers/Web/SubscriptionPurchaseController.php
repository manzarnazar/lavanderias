<?php

namespace App\Http\Controllers\Web;

use App\Enums\Status;
use App\Enums\SubscriptionType;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use App\Repositories\StoreSubscriptionRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;


class SubscriptionPurchaseController extends Controller
{
    public function index()
    {
        $subscriptionTypes = SubscriptionType::cases();
        $statuses = Status::cases();
        $subscriptions = (new SubscriptionRepository())->query()->where('status', 'Active')->get();
        return view('subscriptionPurchase.index', compact('subscriptions', 'subscriptionTypes', 'statuses'));
    }



    public function update(Subscription $subscription)
    {

        $stripeGateway  = PaymentGateway::where('name', 'stripe')->first();
        $razorpayGateway = PaymentGateway::where('name', 'razorpay')->first();
        $payStackGateway = PaymentGateway::where('name', 'paystack')->first();
        $payfastGateway = PaymentGateway::where('name', 'payfast')->first();

        $stripe_publish_key = optional(json_decode($stripeGateway->config))->published_key ?? '';
        $razorpay_publish_key = optional(json_decode($razorpayGateway->config))->key ?? '';
        $paystack_publish_key = optional(json_decode($payStackGateway->config))->public_key ?? '';
        $payfast_client_id = optional(json_decode($payfastGateway->config))->merchant_id ?? '';
        $payfast_client_secret = optional(json_decode($payfastGateway->config))->merchant_key ?? '';

        $data = [
            // 'storeSubscription' => (new StoreSubscriptionRepository())->storeByRequest($subscription),
            'paymentGateways' => (new PaymentGatewayRepository())->query()->where('is_active', 1)->get(),
            'subscription' => $subscription,
            'stripe_publish_key' => $stripe_publish_key,
            'razorpay_publish_key' => $razorpay_publish_key,
            'paystack_publish_key' => $paystack_publish_key,
            'payfast_client_id' => $payfast_client_id,
            'payfast_client_secret' => $payfast_client_secret,
        ];


        return view('subscriptionPurchase.payment', $data);
    }
}
