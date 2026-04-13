<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AppSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function websiteLogo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo');
    }

    public function getWebsiteLogoPathAttribute(): string
    {
        return asset('assets/logo/lav_logo.png');
    }

    public function websiteFavicon(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'fav_icon');
    }

    public function getWebsiteFaviconPathAttribute(): string
    {
        return asset('assets/logo/lav_logo.png');
    }

    public function signature()
    {
        return $this->belongsTo(Media::class, 'signature_id');
    }

    public function getSignaturePathAttribute(): string
    {
        if ($this->signature && Storage::exists($this->signature->src)) {
            return Storage::url($this->signature->src);
        }

        return asset('web/signature.png');
    }
}
