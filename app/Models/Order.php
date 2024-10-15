<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'total_sum',
        'phone',
        'comment'
    ];

    public const CACHE_NAME = 'orders';
    public const CACHE_TIME = 60 * 60 * 20;

    public function items(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
