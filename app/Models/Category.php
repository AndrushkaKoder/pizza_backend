<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Attachment\Attachable;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Attachable;

    protected $fillable = [
        'title',
        'active',
        'max_for_order'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'category_product',
            'category_id',
            'product_id'
        );
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeHasProducts(Builder $query): Builder
    {
        return $query->whereHas('products');
    }

    public function scopeHasImages(Builder $query): Builder
    {
        return $query->whereHas('attachments');
    }

    public function getImages(): array
    {
        return $this->attachments()->get()->map(fn($image) => $image->url())->toArray();
    }
}
