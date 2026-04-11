<?php

namespace App\Http\Controllers\Web;

use App\Enums\SubscriptionType;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;
use App\Models\StoreSubscription;
use App\Repositories\StoreSubscriptionRepository;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = (new SubscriptionRepository())->getAll();
        $subscriptionTypes = SubscriptionType::cases();
        $statuses = Status::cases();
        return view('subscription.index', compact('subscriptions', 'subscriptionTypes', 'statuses'));
    }

    // public function store(SubscriptionRequest $request)
    // {
    //     (new SubscriptionRepository())->storeByRequest($request);
    //     return back()->with('success', 'Subscription is created successfully');
    // }
    public function store(SubscriptionRequest $request)
{
    (new SubscriptionRepository())->storeByRequest($request);

    return response()->json([
        'success' => true,
        'message' => 'Subscription is created successfully'
    ]);
}

    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        (new SubscriptionRepository())->updateByRequest($request, $subscription);
        return back()->with('success', 'Subscription is updated successfully');
    }

    public function statusChanage(Subscription $subscription, $status)
    {
        (new SubscriptionRepository())->statusChanageByRequest($subscription, $status);
        return back()->with('success', 'Subscription successfully chanaged');
    }

    public function report()
    {
        $shopSubscriptions = (new StoreSubscriptionRepository())->query()->orderByDesc('id')->get();
        if ($this->mainShop()) {
            $shopSubscriptions = (new StoreSubscriptionRepository())->query()->where('store_id', $this->mainShop()->id)->orderByDesc('id')->get();
        }
        return view('subscription.report', compact('shopSubscriptions'));
    }
}
