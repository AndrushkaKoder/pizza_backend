<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public const T_PIZZA = 1;
    public const T_DRINK = 2;
    public const T_SNACKS = 3;

    public function canAddMore(int $countProductsInCart): bool
    {
        return $countProductsInCart < $this->max_count;
    }
}
