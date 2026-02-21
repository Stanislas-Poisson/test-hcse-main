<?php

namespace App\Models;

use App\Enums\ProductState;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property ProductState $state
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'name',
        'sku',
        'image',
        'price',
        'state',
    ];

    /**
     * @param  Builder<self>  $builder
     * @return Builder<self>
     */
    protected function scopePublished(Builder $builder): Builder
    {
        return $builder->where('state', ProductState::Published->value);
    }

    /** @return BelongsTo<Offer, $this> */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    protected function casts(): array
    {
        return [
            'state' => ProductState::class,
            'price' => 'decimal:2',
        ];
    }
}
