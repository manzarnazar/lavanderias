<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class CheckHasRootUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rootUser = User::role('root')->get();
        if(!request()->routeIs(['create.root', 'create.superadmin']) && $rootUser->isEmpty()) {
            return redirect()->route('create.root');
        }
        if(!$rootUser->isEmpty() && request()->routeIs(['create.root', 'create.superadmin'])) {
            return redirect()->route('dashboard')->with('error', __('Super Admin already exists. You cannot create another one.'));
        }

        return $next($request);
    }
}
