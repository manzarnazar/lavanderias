<?php

namespace App\Http\Controllers\API\Driver\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\DriverRequest;
use App\Http\Requests\SellerLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\DriverDeviceKey;
use App\Repositories\DriverDeviceKeyRepository;
use App\Repositories\DriverRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Http\Requests\ForgotPasswordOtpVerifyRequest;
use Illuminate\Http\Response;
use App\Services\SMS;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    private $verificationCodeRepo;
    private $userRepo;
    public function __construct(VerificationCodeRepository $verificationCodeRepo, UserRepository $userRepo)
    {
        $this->verificationCodeRepo = $verificationCodeRepo;
        $this->userRepo = $userRepo;
    }
    public function login(SellerLoginRequest $request)
    {
        if ($user = $this->authenticate($request)) {

            if (!$user->is_active) {
                return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
            }

            if ($key = $request->device_key) {
                if (!$this->findByKey($key)) {
                    DriverDeviceKey::create([
                        'driver_id' => $user->driver->id,
                        'key' => $key,
                    ]);
                }
            }

            return $this->json('Log In Successfull', [
                'user' => new UserResource($user),
                'access' => (new UserRepository)->getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }


    public function register(DriverRequest $request)
    {
        $contact =$request->mobile;

        $user = (new UserRepository())->registerUser($request);

        (new DriverRepository())->storeByUser($user);
        $verificationCode = $this->verificationCodeRepo->findOrCreateByContact($contact);

        $user->update([
            'is_active' => false,
        ]);

        if ($request->mobile && config('app.sms_two_step_verification')) {
            $message = 'Welcome to '.config('app.name')."\nYour otp verification code is ".$verificationCode->otp;
            SMS::sendSms($request->mobile, $message);
        }

        return $this->json('Rider created successfully', [
            'rider' => UserResource::make($user),
            'otp' => $verificationCode->otp
        ]);
    }
    public function verifyOtp(ForgotPasswordOtpVerifyRequest $request)
    {
        $contact = $request->{$request->type};
        $user = $this->userRepo->findByContact($contact);

        if (! $user) {
            return $this->json('Sorry! No user found with this mobile number.', [], Response::HTTP_BAD_REQUEST);
        }

        $verificationCode = $this->verificationCodeRepo->checkCode($contact, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid OTP', [], Response::HTTP_BAD_REQUEST);
        }

        if($verificationCode){
            $user->update([
                'mobile_verified_at' => now(),
            ]);
            $verificationCode->delete();

        }
        return $this->json('Otp matched successfully!', [
            'token' => $verificationCode->token,
        ]);
    }
    private function authenticate($request)
    {
        $user = (new UserRepository)->findByContact($request->contact);
        if (!is_null($user) && $user->driver) {
            if (Hash::check($request->password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function findByKey($key)
    {
        return DriverDeviceKey::where('key', $key)->first();
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        $currentPassword = $request->current_password;

        if (Hash::check($currentPassword, $user->password)) {

            if (Hash::check($request->password, $user->password)) {
                return $this->json('New password cannot be same as current password', [], Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password change successfully', [
                'user' => (new UserResource($user)),
            ]);
        }
        return $this->json('Current password is incorrect', [], Response::HTTP_BAD_REQUEST);
    }

    public function show()
    {
        $user = auth()->user();

        return $this->json('user details', [
            'user' => new UserResource($user),
        ]);
    }

    public function logout()
    {
        $user = auth()->user();
        if (\request()->device_key) {
            (new DriverDeviceKeyRepository())->destroy(\request()->device_key);
        }
        if ($user) {
            $user->token()->revoke();

            return $this->json('Logged out successfully!');
        }

        return $this->json('No Logged in user found', [], Response::HTTP_UNAUTHORIZED);
    }
}
