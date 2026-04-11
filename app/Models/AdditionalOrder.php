<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalOrder extends Model
{
    use HasFactory;

    public function additional()
{
    return $this->belongsTo(Additional::class, 'additional_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id'); // or order_id if that's your column
}

}
