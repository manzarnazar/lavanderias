<?php

namespace App\Services;

use App\Repositories\GeneralSettingRepository;
use App\Models\PaymentGateway;
// use Stripe\Stripe;
use Stripe;

class StripePayService
{
    public function paymentProcess($request, $config)
    {

        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();
        $config  = json_decode($paymentGateway->config);

        Stripe\Stripe::setApiKey($config->secret_key);

        $result = Stripe\Charge::create([
            "amount"  => $request->paid_amount * 100,
            "currency" =>'USD',
            "source" => $request->token_id,
            "description" => $request->description ?? '',
        ]);
 
        // dd($result['status']);
        if(($result['status'] == 'succeeded')):
            return true;
        else:
            return false;
        endif;
    }
}

