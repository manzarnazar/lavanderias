<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest as LoginRequest;
use App\Models\AppSetting;
use App\Models\Setting;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $loginRequest)
    {
        $user = $this->isAuthenticate($loginRequest);
        $loginRequest->only('email', 'password');

        if (! $user) {
            return redirect()->back()
                ->withErrors(['email' => ['Invalid credentials']])
                ->withInput();
        }

        Auth::login($user);
        $loginRequest->session()->regenerate();

        return redirect()->route('root');
    }

    private function isAuthenticate($loginRequest)
    {
        $user = (new UserRepository())->findByContact($loginRequest->email);

        if (! is_null($user) && $user->is_active && Hash::check($loginRequest->password, $user->password)) {
            return $user;
        }

        return false;
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
    public function privacyPolicy(){
        $websiteName = AppSetting::all();
        $setting = Setting::where('slug', 'privacy-policy')->first();
        return view('auth.privacy-policy',compact('setting','websiteName'));
    }
    public function termsCondition(){
        $websiteName = AppSetting::all();
        $setting = Setting::where('slug', 'trams-of-service')->first();
        return view('auth.terms-condition',compact('setting','websiteName'));
    }
}
