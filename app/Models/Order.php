<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'delivery_time',
        'address',
        'closed',
        'payment_id',
        'total_sum'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'product_id');
    }

}
