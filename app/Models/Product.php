<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use HasFactory;
    use Attachable;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'title',
        'description',
        'weight',
        'price',
        'type_id',
        'active',
    ];

    protected $allowedSorts = [
        'id',
        'price'
    ];

    public const CACHE_NAME = 'products';
    public const CACHE_TTL = 60 * 60 * 24;

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'category_product',
            'product_id',
            'category_id'
        );
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeHasPrice(Builder $query): Builder
    {
        return $query->where('price', '>', 0);
    }

    public function scopeHasImages(Builder $query): Builder
    {
        return $query->whereHas('attachments');
    }

    public function scopeOrderDesc(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function getImages(): array
    {
        return $this->attachments()->get()->map(fn($image) => $image->url())->toArray();
    }

    public function priceInteger(): int
    {
        return intval($this->price);
    }

    public function isActive(): bool
    {
        return false;
    }

    public function frontendPrice(int $price = null): string
    {
        return ($price ?? $this->priceInteger()) . ' ₽';
    }
}
