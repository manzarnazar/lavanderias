<?php

namespace App\Repositories;

use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\StoreSubscription;
use App\Models\SubscriptionRequest as ModelsSubscriptionRequest;
use Carbon\Carbon;

class StoreSubscriptionRepository extends Repository
{

    public function model()
    {
        return StoreSubscription::class;
    }

    public function storeByRequest($subscription,$request)
    {

        $store = auth()->user()->store;
        $lastSubscription = $store->subscriptions()->where('status', true)->latest('id')->first();

        $startdate = date('Y-m-d');
        $dayLeft = 0;


        if($lastSubscription?->expired_at > date('Y-m-d')){
            $start = Carbon::parse($startdate);
            $end = Carbon::parse($lastSubscription->expired_at);
            $dayLeft = $start->diffInDays($end);
        }

        $totalDay = match($subscription->type->value){
            'Monthly' => 30,
            'Yearly' =>365,
            'Half Yearly' => 180,
            'Quarterly' => 120,
            default =>7
        } + $dayLeft;

        $expireDate = now()->addDays($totalDay)->format('Y-m-d');
        $lastSubscription?->update(['status' => false]);

        return $this->create([
            'store_id' => $store->id,
            'subscription_id' => $subscription->id,
            'status' => true,
            'payment_status' => PaymentStatus::PAID->value,
            'payment_gateway' =>$request,
            'expired_at' => $expireDate
        ]);
    }

}
