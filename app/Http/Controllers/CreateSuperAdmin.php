<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class CreateSuperAdmin extends Controller
{
    public function index()
    {
        return view('create-root');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);


        $localAdmin = User::create([
                'email' => $request->email,
                'mobile' => '011' . rand(100000000, 999999999),
                'first_name' => 'Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);


        $permissions = config('acl.permissions');

        foreach ($permissions as $permission => $value) {
            $localAdmin->givePermissionTo($permission);
        }
        $localAdmin->assignRole('root');
        Auth::login($localAdmin);
        return redirect()->route('root')->with('success', __('You are ready to use ReadyLaundry! Please login with your credentials.'));
    }
}
