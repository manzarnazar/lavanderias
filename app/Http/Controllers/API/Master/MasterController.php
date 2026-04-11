<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentGatewayResource;
use App\Models\AppSetting;
use App\Models\MobileAppUrl;
use App\Models\PaymentGateway;
use App\Models\Store;
use App\Repositories\PaymentGatewayRepository;

class MasterController extends Controller
{
    public function index()
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $mobileAppLink = MobileAppUrl::first();
        $paymentGateway = PaymentGateway::where('is_active', true)->get();

        $SMStwoStepVerification = false;
        if (config('app.sms_base_url') && config('app.sms_user_name') && config('app.sms_password') && config('app.sms_source') && config('app.sms_two_step_verification')) {
            $SMStwoStepVerification = true;
        }

        $emailVerify = config('app.mail_two_step_verification') ? true : false;
        $twoStepVerification = $SMStwoStepVerification == true ? true : $emailVerify;
        $deviceType = $SMStwoStepVerification ? 'mobile' : ($emailVerify ? 'email' : null);

        //payment gateway

        $repository = new PaymentGatewayRepository();
        $storeId = request()->store_id;

        $gateways = $repository->query()->where('is_active', 1)->whereHas('store_payment_gateways', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                })->get();


        return $this->json('All configuration list', [
            'currency' => $currency,
            'android_url' => $mobileAppLink ? $mobileAppLink->android_url : '',
            'ios_url' => $mobileAppLink ? $mobileAppLink->ios_url : '',
            'two_step_verification' => (bool) $twoStepVerification,
            'device_type' => $deviceType,
            'cash_on_delivery' => (bool) true,
            'online_payment' => (bool) false,
            'payment_gateway' => PaymentGatewayResource::collection($gateways),
        ]);
    }

}
