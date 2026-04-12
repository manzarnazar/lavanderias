<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\AddressRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class StoreLocationSyncService
{
    /**
     * Persist store coordinates and sync geocoded address fields (same behaviour as StoreProfileController::location).
     */
    public function syncStoreCoordinates(Store $store, float|string $latitude, float|string $longitude): void
    {
        $store->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        $payload = $this->geocodeToAddressPayload($latitude, $longitude);
        $request = Request::create('/', 'POST', $payload);

        (new AddressRepository())->updateOrCreate($request, $store->fresh());
    }

    /**
     * @return array{address_name: ?string, road_no: ?string, area: ?string, latitude: float|string, longitude: float|string}
     */
    public function geocodeToAddressPayload(float|string $latitude, float|string $longitude): array
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='
            . $latitude . ',' . $longitude
            . '&key=' . mapApiKey();

        $client = new Client(['verify' => false]);

        $address_name = null;
        $road_no = null;
        $area = null;

        try {
            $apiResponse = $client->get($url)->getBody()->getContents();
            $results = json_decode($apiResponse)->results ?? [];

            if (! empty($results)) {
                $index = isset($results[4]) ? 4 : 0;

                $address_name = $results[$index]->formatted_address ?? null;
                $road_no = $results[$index]->address_components[0]->long_name ?? null;
                $area = $results[$index]->address_components[1]->long_name ?? null;
            }
        } catch (\Throwable) {
            // Keep nulls; coordinates are still saved on the store.
        }

        return [
            'address_name' => $address_name,
            'road_no' => $road_no,
            'area' => $area,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}
