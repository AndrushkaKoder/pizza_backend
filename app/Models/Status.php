<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Status extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'title'
    ];

    public const JUST_CREATED = 1;

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'status_id');
    }
}
