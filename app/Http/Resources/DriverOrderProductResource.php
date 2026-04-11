<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DriverOrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discount = null;
        if ($this->price > $this->discount_price && $this->discount_price > 0) {
            $discount = round((($this->price - $this->discount_price) * 100) / $this->price, 2);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'slug' => $this->slug,
            'current_price' =>  (float) $this->discount_price ? $this->discount_price : $this->price,
            'old_price' =>  (float) $this->discount_price ? $this->price : null,
            'description' => $this->description,
            'image_path' => $this->thumbnailPath,
            'discount_percentage' => $discount,
            'qrcode_url' => $this->qrcode_url ? Storage::url($this->qrcode_url) : null,
            'service' => (new ServiceResource($this->service)),
            // 'variant' => (new VariantResource($this->variant)),
            'quantity' => $this->pivot->quantity
        ];
    }
}
