<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'products_count',
        'total_sum'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItems::class, 'cart_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'cart_product',
            'cart_id',
            'product_id'
        );
    }

    public function increaseTotalSum(int $price): void
    {
        $this->update([
            'total_sum' => $this->total_sum + $price
        ]);
    }

    public function decreaseTotalSum(int $price): void
    {
        $this->update([
            'total_sum' => $this->total_sum > 0 ? $this->total_sum - $price : 0
        ]);
    }
}
