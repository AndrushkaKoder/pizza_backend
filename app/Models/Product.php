<?php

namespace App\Models;

use Carbon\Carbon;
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
        'discount_price',
        'discount_end',
        'discount_active'
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

    public function getDiscountPrice(): ?int
    {
        if (!$this->discount_price || !$this->discount_active) return null;
        if ($this->discount_end && Carbon::parse($this->discount_end) < Carbon::now()) return null;
        return $this->getPrice($this->discount_price);
    }

    public function getPrice(int $price = null): int
    {
        return intval($price ?? $this->price);
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function frontendPrice(int $price = null): string
    {
        return ($price ?? $this->priceInteger()) . ' ₽';
    }
}
