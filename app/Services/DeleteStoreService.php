<?php

namespace App\Services;

use App\Models\Additional;
use App\Models\AdditionalOrder;
use App\Models\Address;
use App\Models\AdminDeviceKey;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\DriverDeviceKey;
use App\Models\DriverHistory;
use App\Models\DriverNotification;
use App\Models\DriverOrder;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderSchedule;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Store;
use App\Models\StorePaymentGateway;
use App\Models\StoreSubscription;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DeleteStoreService
{
    public function deleteStore(Store $store): void
    {
        $ownerId = $store->shop_owner;
        $storeId = $store->id;
        $slug = $store->slug;

        DB::transaction(function () use ($store, $ownerId) {
            $orderIds = Order::withoutGlobalScopes()
                ->where('store_id', $store->id)
                ->pluck('id');

            foreach ($orderIds as $orderId) {
                $this->deleteOrderDependencies((int) $orderId);
                Order::withoutGlobalScopes()->where('id', $orderId)->delete();
            }

            $couponIds = Coupon::where('store_id', $store->id)->pluck('id');
            if ($couponIds->isNotEmpty()) {
                DB::table('coupon_users')->whereIn('coupon_id', $couponIds)->delete();
            }
            Coupon::where('store_id', $store->id)->delete();

            Product::where('store_id', $store->id)->delete();
            Variant::where('store_id', $store->id)->delete();

            Additional::where('store_id', $store->id)->delete();
            OrderSchedule::where('store_id', $store->id)->delete();
            StoreSubscription::where('store_id', $store->id)->delete();
            Rating::where('store_id', $store->id)->delete();

            $store->services()->detach();
            DB::table('store_users')->where('store_id', $store->id)->delete();
            DB::table('favourite_stores')->where('store_id', $store->id)->delete();
            DB::table('favourite_store_user')->where('store_id', $store->id)->delete();

            StorePaymentGateway::where('store_id', $store->id)->delete();
            Transaction::where('store_id', $store->id)->delete();

            Address::where('store_id', $store->id)->delete();

            $driverIds = Driver::where('store_id', $store->id)->pluck('id');
            if ($driverIds->isNotEmpty()) {
                DriverNotification::whereIn('driver_id', $driverIds)->delete();
                DriverDeviceKey::whereIn('driver_id', $driverIds)->delete();
                DriverOrder::whereIn('driver_id', $driverIds)->delete();
                DriverHistory::whereIn('driver_id', $driverIds)->delete();
                Driver::whereIn('id', $driverIds)->delete();
            }

            $store->delete();

            $owner = User::find($ownerId);
            if ($owner) {
                $this->deleteShopOwnerUser($owner);
            }
        });

        Cache::forget('user_' . $ownerId);
        Cache::forget('store_' . $storeId);
        Cache::forget('shop_' . $slug);
    }

    private function deleteOrderDependencies(int $orderId): void
    {
        OrderProduct::where('order_id', $orderId)->delete();
        DB::table((new AdditionalOrder())->getTable())->where('order_id', $orderId)->delete();
        Payment::where('order_id', $orderId)->delete();
        DriverHistory::where('order_id', $orderId)->delete();
        DriverOrder::where('order_id', $orderId)->delete();
        Rating::where('order_id', $orderId)->delete();
        Transaction::where('order_id', $orderId)->delete();
    }

    private function deleteShopOwnerUser(User $user): void
    {
        $user->tokens()->delete();

        AdminDeviceKey::where('user_id', $user->id)->delete();
        $user->wallet?->delete();

        $user->syncRoles([]);
        $user->syncPermissions([]);

        $user->delete();
    }
}
