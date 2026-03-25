<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'phone',
    'total',
    'payment_status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
