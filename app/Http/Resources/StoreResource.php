<?php

namespace App\Http\Resources;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $total = $this->ratings->sum('rating');
        $totalPerson = $this->ratings->count();
        $request = \request();
        $distance = getDistance([$request->latitude, $request->longitude], [$this->latitude, $this->longitude]);
        $user = auth('api')->user();
        $favourite = false;

        if ($user !== null && $user->customer) {
            $favourite = $user->customer->favouriteStore
                ->contains('id', $this->id);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_favourite' => $favourite,
            'owner' => UserResource::make($this->user),
            'logo' => $this->logoPath,
            'banner_id' => $this->bannerPath,
            'delivery_charge' => (float) $this->delivery_charge,
            'min_order_amount' =>  (float) $this->min_order_amount,
            'max_order_amount' =>  (float) $this->max_order_amount,
            'prifix' => $this->prifix,
            'description' => $this->description,
            'commission' => $this->commission,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'distance' => round($distance, 2).'km',
            'total_rating' => (int) $totalPerson,
            'average_rating' => number_format(($totalPerson ? round(($total / $totalPerson), 1) : 5), 1),
            'address' => AddressResource::make($this->address),
        ];
    }
}
