<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['thumbnail_path'];


    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->name);
            }
        });
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

    // ----------Relations
    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_services')->withPivot(['service_id', 'store_id']);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'service_id');
    }

    public function aditionalServices()
    {
        return $this->hasMany(Additional::class);
    }

    public function additionalServices()
    {
        return $this->hasMany(Additional::class, 'service_id');
    }



    public function additionals()
    {
        return $this->belongsToMany(Additional::class, AdditionalService::class);
    }

    // --------- Attributes
    public function getThumbnailPathAttribute()
    {
        // dd(Storage::exists($this->thumbnail->src));
        if ($this->thumbnail && Storage::exists($this->thumbnail->src)) {
            return asset(Storage::url($this->thumbnail->src));
        }

        return asset('images/dummy/dummy-placeholder.png');

    }

    //---------- Scopes
    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('is_active', true);
    }
}
