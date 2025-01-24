<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne(Product::class,'id', 'product_id');
    }
}
