<?php

namespace App\Repositories;

use App\Enums\Roles;
use App\Http\Requests\ShopRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Models\AppSetting;
use App\Models\OrderSchedule;
use App\Models\Store;
use App\Models\StoreSubscription;
use App\Models\Wallet;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class StoreRepository extends Repository
{
    private $path = 'images/shops/';


    public function model()
    {
        return new Store();
    }

    public function storeByRequest(ShopRequest $request): Store
    {
        $user = (new UserRepository())->registerUser($request, true);

        Wallet::create([
            'user_id' => $user->id,
        ]);

        $role = Role::where('name', Roles::STORE->value)->first();
        $permissions = $role->getPermissionNames()->toArray();

        $user->assignRole(Roles::STORE->value);
        $user->givePermissionTo($permissions);

        $logoId = $this->uploadImage($request, 'logo');
        $bannerId = $this->uploadImage($request, 'banner');
        $shopSignatureId = $this->uploadImage($request, 'shop_signature');


        return $this->create([
            'shop_owner' => $user->id,
            'logo_id' => $logoId,
            'banner_id' => $bannerId,
            'shop_signature_id' => $shopSignatureId,
            'name' => $request->name,
            'commission' => $request->commission ?? 0,
            'commission_due_limit'  => $request->commission_due_limit ?? 0,
            'description' => $request->description,
            'status' => true,
            'prifix' => $request->prefix ?? 'IM',
        ]);
    }
    public function storeByWeb($request, $user)
    {

        $role = Role::where('name', Roles::STORE->value)->first();
        $permissions = $role->getPermissionNames()->toArray();
        $user->assignRole(Roles::STORE->value);
        $user->givePermissionTo($permissions);

        $store = $this->create([
            'shop_owner' => $user->id,
            'name' => $request->business_name,
            'status' => true,
        ]);

        if (method_exists($user, 'shopUser')) {
            $user->shopUser()->attach($store->id);
        }


        $this->createSchedule($store);

        return $store;
    }
    public function getNearestStoresByService($serviceSlug, $data)
    {
        $stores = $this->query()
            ->when($serviceSlug, function ($query) use ($serviceSlug) {
                $query->whereHas('services', function ($query) use ($serviceSlug) {
                    $query->where('services.slug', $serviceSlug);
                });
            })->where('status', true)->whereNotNull('latitude')->whereNotNull('longitude')->get();

        $availableStores = $this->getInsideStoresFromPolygon($data, $stores);

        $nearest = [];
        foreach ($availableStores as $store) {
            $distance = getDistance(
                [$data->latitude, $data->longitude],
                [$store->latitude, $store->longitude]
            );
            $nearest[] = ['distance' => round($distance, 2), 'store' => $store];
        }

        ksort($nearest);

        return $nearest;
    }

    public function getNearestStores($data)
    {
        $appSetting = AppSetting::first();

        $stores = $this->query()
            ->where('status', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $validStores = collect();

        foreach ($stores as $store) {


            if ($appSetting && $appSetting->business_based_on === 'commission') {
                if ($store->commission_wallet < $store->commission_due_limit) {
                    $validStores->push($store);
                }
                continue;
            }


            $storeSubscription = StoreSubscription::where('store_id', $store->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($storeSubscription && $storeSubscription->expired_at) {

                $dueDate = Carbon::now()
                    ->diffInDays(Carbon::parse($storeSubscription->expired_at), false);

                if ($dueDate > 0) {
                    $store->due_date = $dueDate;
                    $validStores->push($store);
                }
            }
        }

        $availableStores = $this->getInsideStoresFromPolygon($data, $validStores);

        $nearest = [];

        foreach ($availableStores as $store) {

            $distance = getDistance(
                [$data->latitude, $data->longitude],
                [$store->latitude, $store->longitude]
            );

            $nearest[] = [
                'distance' => round($distance, 2),
                'store' => $store
            ];
        }

        return collect($nearest)
            ->sortBy('distance')
            ->values()
            ->all();
    }




    function getDistance($point1, $point2)
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($point1[0]);
        $lonFrom = deg2rad($point1[1]);
        $latTo   = deg2rad($point2[0]);
        $lonTo   = deg2rad($point2[1]);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $earthRadius * $angle; // distance in km
    }


    public function getByNearest($data)
    {
        $service = $data->service_id;
        $search = $data->search;

        $stores = $this->query()->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })->when($service, function ($query) use ($service) {
            $query->whereHas('services', function ($query) use ($service) {
                $query->where('id', $service);
            });
        })->whereHas('user', function ($query) {
            $query->where('is_active', true);
        })->whereNotNull('longitude')->whereNotNull('latitude')->get();

        $availableStores = $this->getInsideStoresFromPolygon($data, $stores);

        $nearest = [];
        foreach ($availableStores as $store) {
            $distance = getDistance([$data->latitude, $data->longitude], [$store->latitude, $store->longitude]);
            $nearest[(string) round($distance, 2)] = $store;
        }
        return collect($nearest)
            ->sortBy('distance')
            ->values()
            ->all();
        // ksort($nearest);

        // return $nearest;
    }

    private function getInsideStoresFromPolygon($data, $stores)
    {
        $availableStores = [];

        foreach ($stores as $store) {
            if ($store->area) {
                $boundaryAreas = [];

                foreach ($store->area->latLngs as $latlng) {
                    $boundaryAreas[] = [
                        (float) $latlng->pivot->lat,
                        (float) $latlng->pivot->lng
                    ];
                }

                // Check if the user latitude and longitude location is within the area
                $userPoint = [$data->latitude, $data->longitude];

                if ($this->isPointInPolygon($boundaryAreas, $userPoint)) {
                    $availableStores[] = $store;
                }
            } else {
                $availableStores[] = $store;
            }
        }

        return $availableStores;
    }

    private function isPointInPolygon($polygon, $point)
    {
        $verticesX = array_column($polygon, 0);
        $verticesY = array_column($polygon, 1);
        $pointsCount = count($polygon);
        $i = $j = $c = 0;

        for ($i = 0, $j = $pointsCount - 1; $i < $pointsCount; $j = $i++) {
            if (((($verticesY[$i] <= $point[1]) && ($point[1] < $verticesY[$j])) || (($verticesY[$j] <= $point[1]) && ($point[1] < $verticesY[$i]))) &&
                ($point[0] < ($verticesX[$j] - $verticesX[$i]) * ($point[1] - $verticesY[$i]) / ($verticesY[$j] - $verticesY[$i]) + $verticesX[$i])
            ) {
                $c = !$c;
            }
        }
        return $c;
    }

    private function uploadImage($request, $name): ?int
    {
        $thumbnail = null;
        if ($request->hasFile($name)) {
            $thumbnail = (new MediaRepository())->storeByRequest(
                $request->{$name},
                $this->path,
                'shop ' . $name,
                'image'
            );
            $thumbnail = $thumbnail->id;
        }

        return $thumbnail;
    }

    public function updateByRequest(ShopRequest $request, Store $store): Store
    {
        (new UserRepository())->update($store->user, [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'date_of_birth' => $request->date_of_birth ?? $store->user->date_of_birth,
        ]);

        $userData = [];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        if (!empty($userData)) {
            (new UserRepository())->update($store->user, $userData);
        }

        (new UserRepository())->update($store->user, $userData);

        $thumbnailLogo = $this->logoUpdate($request, $store);
        $thumbnailBanner = $this->bannerUpdate($request, $store);
        $thumbnailSignature = $this->signatureUpdate($request, $store);

        $this->update($store, [
            'logo_id' => $thumbnailLogo ? $thumbnailLogo->id : null,
            'banner_id' => $thumbnailBanner ? $thumbnailBanner->id : null,
            'shop_signature_id' => $thumbnailSignature ? $thumbnailSignature->id : $store->shop_signature_id,
            'name' => $request->name,
            'commission' => $request->commission,
            'commission_due_limit'  => $request->commission_due_limit ?? 0,
            'description' => $request->description,
        ]);

        return $store;
    }

    public function updateOnlyStoreByRequest(StoreUpdateRequest $request, Store $store): Store
    {
        $thumbnailLogo = $this->logoUpdate($request, $store);
        $thumbnailBanner = $this->bannerUpdate($request, $store);

        $this->update($store, [
            'logo_id' => $thumbnailLogo ? $thumbnailLogo->id : null,
            'banner_id' => $thumbnailBanner ? $thumbnailBanner->id : null,
            'name' => $request->name,
            'delivery_charge' => $request->delivery_charge ?? $store->delivery_charge,
            'min_order_amount' => $request->min_order_amount ?? $store->min_order_amount,
            'description' => $request->description,
            'prifix' => $request->prefix ?? $store->prifix,
        ]);

        return $store;
    }

    public function bannerUpdate($request, $store)
    {
        $thumbnail = $store->banner;
        if ($request->hasFile('banner') && $thumbnail == null) {
            $thumbnail = (new MediaRepository())->storeByRequest(
                $request->banner,
                $this->path,
                'shop images',
                'image'
            );
        }

        if ($request->hasFile('banner') && $thumbnail) {
            $thumbnail = (new MediaRepository())->updateOrCreateByRequest(
                $request->banner,
                $this->path,
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }

    public function logoUpdate($request, $store)
    {
        $thumbnail = $store->logo;
        if ($request->hasFile('logo') && $thumbnail == null) {
            $thumbnail = (new MediaRepository())->storeByRequest(
                $request->logo,
                $this->path,
                'shop images',
                'image'
            );
        }

        if ($request->hasFile('logo') && $thumbnail) {
            $thumbnail = (new MediaRepository())->updateOrCreateByRequest(
                $request->logo,
                $this->path,
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }

    public function createSchedule($store)
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($days as $day) {
            OrderSchedule::create([
                'store_id' => $store->id,
                'day' => $day,
                'start_time' => 8,
                'end_time' => 16,
                'per_hour' => 1,
                'is_active' => true,
                'type' => 'pickup',
            ]);
        }

        foreach ($days as $day) {
            OrderSchedule::create([
                'store_id' => $store->id,
                'day' => $day,
                'start_time' => 8,
                'end_time' => 16,
                'per_hour' => 1,
                'is_active' => true,
                'type' => 'delivery',
            ]);
        }
    }

    protected function signatureUpdate(ShopRequest $request, Store $store)
    {
        if ($request->hasFile('shop_signature')) {
            $store->shop_signature_path = $request->file('shop_signature');
        }
    }
}
