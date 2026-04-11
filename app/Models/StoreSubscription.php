<?php

namespace App\Models;

use App\Enums\IsHas;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSubscription extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    protected $casts = [
        // 'payment_gateway' => PaymentGateway::class,
        'payment_status' => PaymentStatus::class,
        'is_current' => IsHas::class,
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
