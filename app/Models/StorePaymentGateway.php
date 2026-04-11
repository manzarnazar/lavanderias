<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorePaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
