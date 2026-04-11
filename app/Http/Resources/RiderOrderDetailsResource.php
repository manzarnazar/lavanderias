<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderOrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = $this->address?->house_no . ', ' . $this->address?->road_no . ', ' . $this->address?->address_line . ', ' . $this->address?->area . ($this->address?->post_code ? ', ' . $this->address?->post_code : '');

        $productGroup = $this->products->groupBy('service_id');

        $data = [];
        foreach($productGroup as $key => $products){
            $service = Service::find($key);
            $productNames = [];
            foreach($products as $product){
                $productNames[] = ['name' => $product->name, 'quantity' => $product->pivot->quantity];
            }

            $data[] = [
                'service' =>  $service->name,
                'products' =>  $productNames
            ];
        }

        return [
            'id' => $this->id,
            'order_code' => $this->order_code,
            'prefix' => $this->prefix,
            'store_id' => $this->store_id,
            'coupon_id' => $this->coupon_id,
            'pos_order' => $this->pos_order,
            'pick_date' => $this->pick_date,
            'store_id' => $this->store_id,
            'coupon_id' => $this->coupon_id,
            'pos_order' => $this->pos_order,
            'delivery_hour' => $this->delivery_hour,
            'payable_amount' => $this->payable_amount,
            'total_amount' => $this->total_amount,
            'discount' => $this->discount,
            'delivery_charge' => $this->delivery_charge,
            'payment_status' => $this->payment_status,
            'payment_type' => $this->payment_type,
            'instruction' => $this->instruction,
            'assign_for' => $this->assign_for,
            'pick_hour' => $this->pick_hour,
            'delivery_date' => $this->delivery_date,
            'user_name' => $this->customer?->user?->name,
            'address' => $address,
            // 'order_status' => $this->order_status,
            'customer' => UserResource::make($this->customer?->user),
            'products' => $data,
            'order_status' => $this->driverOrder->status
        ];
    }
}
