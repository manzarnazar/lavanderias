<?php

namespace App\Http\Controllers\API\Master;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentGatewayResource;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class SellerMasterController extends Controller
{
    public function index(){
        $storeId = request()->store_id;

        if(!$storeId){
            return $this->json('Please select a store id');
        }

        $paymentGateway = PaymentGateway::where('is_active', true)->where('store_id', $storeId)->get();
        return $this->json('',[
            'payment_gateway' => PaymentGatewayResource::collection($paymentGateway),

        ]);
    }

    }

