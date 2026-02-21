<?php

namespace Tests\Unit;

use App\Enums\OfferState;
use App\Models\Offer;
use App\Models\Product;
use App\Queries\CatalogueQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueQueryTest extends TestCase
{
    use RefreshDatabase;

    /** @phpstan-ignore property.uninitialized */
    private CatalogueQuery $catalogueQuery;

    protected function setUp(): void
    {
        parent::setUp();
        $this->catalogueQuery = new CatalogueQuery;
    }

    public function test_returns_only_published_offers(): void
    {
        Offer::factory()->published()->create();
        Offer::factory()->draft()->create();
        Offer::factory()->hidden()->create();

        $publishedOffers = $this->catalogueQuery->getPublishedOffers();

        $this->assertCount(1, $publishedOffers);
        $first = $publishedOffers->first();
        $this->assertNotNull($first);
        $this->assertSame(OfferState::Published, $first->state);
    }

    public function test_eager_loads_products_relationship(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->published()->count(2)->create(['offer_id' => $offer->id]);

        $publishedOffers = $this->catalogueQuery->getPublishedOffers();

        $first = $publishedOffers->first();
        $this->assertNotNull($first);
        $this->assertTrue($first->relationLoaded('products'));
        $this->assertCount(2, $first->products);
    }

    public function test_excludes_non_published_products_from_results(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->published()->create(['offer_id' => $offer->id]);
        Product::factory()->draft()->create(['offer_id' => $offer->id]);
        Product::factory()->invisible()->create(['offer_id' => $offer->id]);

        $publishedOffers = $this->catalogueQuery->getPublishedOffers();

        $first = $publishedOffers->first();
        $this->assertNotNull($first);
        $this->assertCount(1, $first->products);
    }

    public function test_returns_empty_collection_when_no_published_offers(): void
    {
        Offer::factory()->draft()->create();

        $publishedOffers = $this->catalogueQuery->getPublishedOffers();

        $this->assertEmpty($publishedOffers);
    }
}
