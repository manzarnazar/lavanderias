<?php

namespace App\Http\Controllers\Web\Root;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShopRequest;
use App\Models\OrderSchedule;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\ServiceRepository;
use App\Repositories\StoreRepository;
use App\Services\DeleteStoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    public function __construct(
        private StoreRepository $storeRepository,
        private DeleteStoreService $deleteStoreService
    ) {}

    public function index()
    {
        $shops = $this->storeRepository->getAll();

        return view('shop.index', compact('shops'));
    }

    public function storeOnMap()
    {
        return view('shop.map-view');
    }

    public function shopLocation()
    {
        $shops = (new StoreRepository())->getAll();
        $latLng = [];
        foreach ($shops as $shop) {
            if ($shop->latitude && $shop->longitude) {
                $latLng[] = [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'phone' => $shop->user->mobile,
                    'email' => $shop->user->email,
                    'lat' => $shop->latitude,
                    'lng' => $shop->longitude,
                ];
            }
        }

        return $latLng;
    }

    public function create()
    {
        return view('shop.create');
    }

    public function show(Store $store)
    {
        $transactions = $store->transactions()->get();
        $orders = $store->orders()->latest('id')->take(10)->get();
        $wallet = $store->user->wallet;
        $withdraws = $store->transactions()->where('is_withdraw', true)->latest('id')->paginate(10);

        return view('shop.show', compact('wallet', 'store', 'transactions', 'orders', 'withdraws'));
    }

    public function transaction(Wallet $wallet)
    {
        return view('transaction.index', compact('wallet'));
    }

    public function order(Store $store)
    {
        $orders = $store->orders;
        $orderStatus = OrderStatus::cases();

        return view('orders.index', compact('orders', 'orderStatus'));
    }

    public function store(ShopRequest $request)
    {
        $store = $this->storeRepository->storeByRequest($request);
        $user = auth()->user();
        $user->shopUser()->attach($store->id);
        $this->storeRepository->createSchedule($store);

        return to_route('shop.index')->with('success', 'Created Successfully');
    }

    public function edit(Store $store)
    {
        $user = $store->user;

        return view('shop.edit', compact('store', 'user'));
    }

    public function update(ShopRequest $request, Store $store)
    {
        $this->storeRepository->updateByRequest($request, $store);

        return to_route('shop.index')->with('success', 'Updated Successfully');
    }

    public function commissionUpdate(Request $request, Store $store)
    {
        $store->update([
            'commission' => $request->commission,
        ]);

        return redirect()->back()->with('success', 'Commission Updated Successfully');
    }

    public function service(Store $store)
    {
        $services = (new ServiceRepository())->getAll();
        $selectedServices = $store->services()->pluck('id')->toArray();

        return view('shop.services', compact('services', 'store', 'selectedServices'));
    }


    public function toggle(User $user)
    {
        $store = Store::where('shop_owner', $user->id)->first();

        if (!$store) {
            return back()->with('error', 'Store not found');
        }

        // Toggle store status
        $store->status = !$store->status;
        $store->save();

        // Make user is_active same as store status
        $user->is_active = $store->status;
        $user->save();

        // Clear cache
        Cache::forget('user_' . $user->id);
        Cache::forget('store_' . $store->id);
        Cache::forget('shop_' . $store->slug);

        return back()->with('success', 'Status Updated Successfully');
    }




    public function serviceUpdate(Store $store, Request $request)
    {
        $store->services()->sync($request->services);

        return to_route('shop.index')->with('success', 'Services added successfully');
    }

    public function product(Store $store)
    {
        $products = $store->products;

        return view('shop.product', compact('products', 'store'));
    }

    public function destroy(Store $store)
    {
        try {
            $this->deleteStoreService->deleteStore($store);

            return to_route('shop.index')->with('success', __('Shop deleted successfully'));
        } catch (\Throwable $e) {
            Log::error('Store cascade delete failed', [
                'store_id' => $store->id,
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            return back()->with('error', __('Could not delete shop. Please try again or contact support.'));
        }
    }
}
