<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\AppSetting;
use App\Models\Store;
use App\Models\StoreSubscription;
use Carbon\Carbon;

class SideBar extends Component
{
    public $appSetting;
    public bool $canAccess = false;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $authId = auth()->user()->id;

        // Load global app settings
        $this->appSetting = AppSetting::first();
        // Load the store for the logged in user
        $store = Store::where('shop_owner', $authId)->first();

        if ($store) {
            if($this->appSetting->business_based_on === 'commission') {
                $storeDue = (float) $store->commission_wallet;
                $storeDueLimit = (float) $store->commission_due_limit;
                $shouldHideSidebar = $this->appSetting->is_commission_due;

                $this->canAccess = true;
                if($shouldHideSidebar){
                    if($storeDueLimit > 0 &&  $storeDue < $storeDueLimit) {
                        $this->canAccess = true;
                    } else {
                        $this->canAccess = false;
                    }
                }
            }

            if($this->appSetting->business_based_on === 'subscription') {
                $storeSubscription = StoreSubscription::where('store_id', $store->id)
                    ->orderBy('expired_at', 'desc')
                    ->first();

                if ($storeSubscription && $storeSubscription->expired_at) {
                    $dueDate = now()->diffInDays(
                        Carbon::parse($storeSubscription->expired_at),
                        false
                    );
                    $this->canAccess = $dueDate > 0;
                }
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.side-bar');
    }
}



