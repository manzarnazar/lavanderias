<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\AppSetting;
use App\Models\StoreSubscription;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = (new UserRepository())->find(auth()->id());
        $userPermissions = $user->getPermissionNames()->toArray();
        $userRole = $user->getRoleNames()->toArray()[0];
        $role = Role::where('name', $userRole)->first();
        $rolePermissions = $role->getPermissionNames()->toArray();

        $allPermissions = array_merge($userPermissions, $rolePermissions);
        $allPermissions = array_unique($allPermissions);

        $requestRoute = request()->route()->getName();

        $appSetting = AppSetting::first();

        if (in_array($requestRoute, $allPermissions) || $userRole === 'root') {
            return $next($request);
        } elseif ($userRole === 'store') {
            $subscription = $user->store->subscriptions()->where('status', true)->first();
            if ($appSetting->business_based_on === 'subscription' && (! $subscription || $subscription->expired_at < date('Y-m-d'))) {
                return to_route('subscription.purchase.index')->with('error', 'Please purchase subscription');
            }

            return back()->with('error', 'Sorry, You have no permission');
        }

        return back()->with('error', 'Sorry, You have no permission');
    }
}
