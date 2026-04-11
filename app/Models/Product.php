<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //------------ Relations

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // public function variant()
    // {
    //     return $this->belongsTo(Variant::class);
    // }
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }


    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, (new OrderProduct())->getTable())
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    //------------ Attributes
    public function getThumbnailPathAttribute()
    {
        if ($this->thumbnail && Storage::exists($this->thumbnail->src)) {
            return asset(Storage::url($this->thumbnail->src));
        }

        return asset('images/dummy/dummy-placeholder.png');
    }

    //----------- Scopes
    public function scopeIsActive(Builder $builder, bool $activity = true)
    {
        return $builder->where('is_active', $activity);
    }
}
