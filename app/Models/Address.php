<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    // ----------- Relations
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
