<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;

class Product extends Model
{
    use HasFactory;
    use Attachable;

    protected $fillable = [
        'title',
        'description',
        'weight',
        'price',
        'type_id',
        'active',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }
}
