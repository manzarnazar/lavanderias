<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\StoreAddressUpdateRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Models\Store;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        return view('store.index', compact('store'));
    }

    public function edit()
    {
        $store = auth()->user()->store;

        return view('store.edit', compact('store'));
    }

    public function update(StoreUpdateRequest $request, Store $store)
    {
        $thumbnailLogo = (new StoreRepository())->logoUpdate($request, $store);
        $thumbnailBanner = (new StoreRepository())->bannerUpdate($request, $store);

        if ($request->hasFile('shop_signature')) {
            $store->shop_signature_path = $request->file('shop_signature');
            $store->save();
        }

        (new StoreRepository())->update($store, [
            'name' => $request->name,
            'delivery_charge' => $request->delivery_charge,
            'service_time' => $request->service_time ?? null,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'logo_id' => $thumbnailLogo ? $thumbnailLogo->id : null,
            'banner_id' => $thumbnailBanner ? $thumbnailBanner->id : null,
            'description' => $request->description,
            'prifix' => $request->prefix,
        ]);

        return to_route('store.index')->with('success', 'Shop Updated Successfully');
    }



    public function userUpdate(UserRequest $request, User $user)
    {
        (new UserRepository())->updateByRequest($request, $user);

        return back()->with('success', 'Profile Updated Successfully');
    }


    public function location(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);


        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='
            . $request->lat . ',' . $request->lng
            . '&key=' . mapApiKey();

        $client = new \GuzzleHttp\Client(['verify' => false]);


        $apiResponse = $client->get($url)->getBody()->getContents();
        $results = json_decode($apiResponse)->results ?? [];

        $store = auth()->user()->store;


        $store->update([
            'latitude' => $request->lat,
            'longitude' => $request->lng,
        ]);


        if (!empty($results)) {
            $index = isset($results[4]) ? 4 : 0;

            $request['address_name'] = $results[$index]->formatted_address ?? null;
            $request['road_no'] = $results[$index]->address_components[0]->long_name ?? null;
            $request['area'] = $results[$index]->address_components[1]->long_name ?? null;
        } else {
            $request['address_name'] = null;
            $request['road_no'] = null;
            $request['area'] = null;
        }

        $request['latitude'] = $request->lat;
        $request['longitude'] = $request->lng;

        (new AddressRepository())->updateOrCreate($request, $store);

        return back()->with('success', 'Location is updated successfully.');
    }


    public function updateAddress(StoreAddressUpdateRequest $request, Store $store)
    {
        // dd($request->all());

        $store->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        (new AddressRepository())->updateOrCreate($request, $store);

        return to_route('store.index')->with('success', 'Address updated succesfully');
    }
}
