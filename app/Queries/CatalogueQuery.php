<?php

namespace App\Queries;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

/**
 * Encapsulates the visibility rules for the public catalogue.
 *
 * Single source of truth for: what makes an offer or product visible
 * to end users. Controllers must not inline these rules.
 */
class CatalogueQuery
{
    /**
     * Returns published offers with only their published products.
     *
     * @return Collection<int, Offer>
     */
    public function getPublishedOffers(): Collection
    {
        return Offer::published()
            ->with(['products' => fn ($query) => $query->published()])
            ->get();
    }
}
