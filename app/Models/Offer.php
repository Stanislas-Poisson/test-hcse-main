<?php

namespace App\Models;

use App\Enums\OfferState;
use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property OfferState $state
 */
class Offer extends Model
{
    /** @use HasFactory<OfferFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'state',
    ];

    /**
     * @param  Builder<self>  $builder
     * @return Builder<self>
     */
    protected function scopeOfState(Builder $builder, string|OfferState $state): Builder
    {
        return $builder->where('state', $state instanceof OfferState ? $state->value : $state);
    }

    /**
     * @param  Builder<self>  $builder
     * @return Builder<self>
     */
    protected function scopePublished(Builder $builder): Builder
    {
        return $builder->where('state', OfferState::Published->value);
    }

    /** @return HasMany<Product, $this> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected function casts(): array
    {
        return [
            'state' => OfferState::class,
        ];
    }
}
