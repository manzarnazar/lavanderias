<?php
namespace App\Repositories;

use App\Http\Requests\PaymentGatewayRequest;
use App\Models\Store;
use App\Models\PaymentGateway;

class PaymentGatewayRepository extends Repository
{
    public function model()
    {
        return PaymentGateway::class;
    }

    public function updateByRequest(PaymentGatewayRequest $request, PaymentGateway $paymentGateway): PaymentGateway
    {

        $config = json_encode($request->config);
        $media = $paymentGateway->media;

        $storId = auth()->user()->id;
        $role = auth()->user()->getRoleNames()[0] ?? 'Admin';

        if($role === 'store'){
            $store = Store::where('shop_owner', $storId)->first();
            $storeId = $store->shop_owner;
        }

        if ($request->hasFile('logo')) {
            $media = (new MediaRepository())->updateOrCreateByRequest($request->logo, 'gateway/logo', 'image', $media);
        }
        $paymentGateway->update([
            'mode' => $request->mode,
            'title' => $request->title,
            'media_id' => $media->id ?? null,
            'config' => $config,
            'store_id' =>  $storeId ?? null
        ]);

        return $paymentGateway;
    }


    // public static function storeOrUpdate(PaymentGatewayRequest $request, PaymentGateway $paymentGateway): PaymentGateway
    //     {
    //         $config = json_encode($request->config);
    //         $shop = Shop::where('user_id', auth()->user()->id)->first();

    //         $paymentGateway = PaymentGateway::where('shop_id', $shop->id)
    //                                         ->where('name', $request->name)
    //                                         ->first();
    //         $mediaId = null;
    //         if ($request->hasFile('logo')) {
    //             $mediaId = MediaRepository::updateOrCreateByRequest($request->logo, 'gateway/logo', 'Image', $paymentGateway?->media)->id;
    //         }

    //         return PaymentGateway::updateOrCreate(
    //             [
    //                 'shop_id' => $shop->id,
    //                 'name' => $request->name,
    //             ],[
    //                 'alias' => $request->name,
    //                 'mode' => $request->mode,
    //                 'title' => $request->title,
    //                 'media_id' =>  $mediaId,
    //                 'config' => $config,
    //             ]
    //         );
    //     }

    // public static function shopWiseGateway()
    // {
    //     $shop = Shop::where('user_id', auth()->user()->id)->first();
    //     $paymentGateways = PaymentGateway::where('shop_id', $shop->id)->get();
    //     return $paymentGateways;
    // }




}
