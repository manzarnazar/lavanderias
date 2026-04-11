<?php

namespace App\Repositories;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;

class SubscriptionRepository extends Repository
{
    public function model()
    {
        return Subscription::class;
    }

    public function storeByRequest(SubscriptionRequest $request)
    {

        return $this->create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'status' => $request->status,
        ]);
    }

    public function updateByRequest(SubscriptionRequest $request, Subscription $subscription)
    {
        return $this->update($subscription, [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'status' => $request->status,
        ]);
    }

    public function statusChanageByRequest(Subscription $subscription, $status)
    {
        return $this->update($subscription, [
            'status' => $status,
        ]);
    }
}
