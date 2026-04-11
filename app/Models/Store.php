<?php

namespace App\Models;

use App\Repositories\MediaRepository;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->name);
            }
        });
    }

    public function customersWhoFavorited()
    {
        return $this->belongsToMany(Customer::class, 'favourite_stores');
    }


    public static function generateUniqueSlug($value)
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    // --------------- Relationships ------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'shop_owner');
    }

    public function subscriptions()
    {
        return $this->hasMany(StoreSubscription::class);
    }

    public function subscription()
    {
        return $this->hasMany((Subscription::class));
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, (new StoreService())->getTable())->withPivot(['store_id', 'service_id']);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function aditionalServices()
    {
        return $this->hasMany(Additional::class);
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'store_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'store_id');
    }

    public function schedules()
    {
        return $this->hasMany(OrderSchedule::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'store_id');
    }

    public function area(): HasOne
    {
        return $this->hasOne(Area::class);
    }

    public function logoPath(): Attribute
    {
        $logo = asset('images/dummy/dummy-user.png');

        if ($this->logo && Storage::exists($this->logo->src)) {
            $logo = asset(Storage::url($this->logo->src));
        }

        return new Attribute(
            get: fn() => $logo
        );
    }

    public function bannerPath(): Attribute
    {
        $banner = asset('images/dummy/dummy-user.png');

        if ($this->banner && Storage::exists($this->banner->src)) {
            $banner = asset(Storage::url($this->banner->src));
        }

        return new Attribute(
            get: fn() => $banner
        );
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_id');
    }
    // app/Models/Store.php
    public function favouritedBy()
    {
        return $this->belongsToMany(User::class, 'favourite_store_user', 'store_id', 'user_id');
    }
    public function shopSignature()
    {
        return $this->belongsTo(Media::class, 'shop_signature_id');
    }
    protected function shopSignaturePath(): Attribute
    {
        return Attribute::make(


            get: fn() =>
            $this->shopSignature?->src
                ? asset('storage/' . $this->shopSignature->src)
                : 'https://placehold.jp/250x250.png',


            set: function ($file) {

                if (!$file) {
                    return null;
                }

                $media = (new MediaRepository())->storeByRequest($file, 'shop-signatures');


                return [
                    'shop_signature_id' => $media->id
                ];
            }
        );
    }
}
