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
    'stripe_checkout_session_id',
    'stripe_payment_intent_id',
    'paid_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
