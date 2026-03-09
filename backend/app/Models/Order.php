<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order_item;
use App\Models\Payment_method;


class Order extends Model
{
     protected $fillable = [
        'user_id',
        'payment_method_id',
        'delivery_address',
        'phone_number',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(Payment_method::class);
    }

    public function orderItems()
    {
        return $this->hasMany(Order_item::class);
    }
}
