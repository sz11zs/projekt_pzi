<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order_item;
use App\Models\Payment_method;


class Product extends Model
{
     protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'quantity',
        'unit',
        'porijeklo',
        'klasa',
        'image',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(Order_item::class);
    }
}
