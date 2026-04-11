<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payment_method' => PaymentGateway::class,
    ];

    /**
     * Get media
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    /**
     * Get logo
     */
    public function logo(): Attribute
    {
        $logo = asset('gateway/logo/Stripe.png');
        if ($this->media) {
            $logo = asset(Storage::url($this->media->src));
        }

        return Attribute::make(
            get: fn() => $logo
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('payment_gateway');
        });

        static::updated(function () {
            Cache::forget('payment_gateway');
        });

        static::deleted(function () {
            Cache::forget('payment_gateway');
        });
    }

    public function store_payment_gateways()
    {
        return $this->hasMany(StorePaymentGateway::class, 'payment_gateway_id');
    }
}
