<?php

namespace App\Models;

use App\Enums\Status;
use App\Enums\SubscriptionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    protected $casts = [
        'type' => SubscriptionType::class,
        'status' => Status::class,
    ];




}
