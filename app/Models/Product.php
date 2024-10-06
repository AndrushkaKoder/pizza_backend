<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeHasPrice(Builder $query): Builder
    {
        return $query->where('price', '>', 0);
    }

    public function getImages(): array
    {
        return $this->attachments()->get()->map(fn($image) => $image->url())->toArray();
    }

    public function priceInteger(): int
    {
        return intval($this->price);
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function frontendPrice(int $price = null): string
    {
        return ($price ?? $this->priceInteger()) . ' ₽';
    }
}
