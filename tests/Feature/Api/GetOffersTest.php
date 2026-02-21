<?php

namespace Tests\Feature\Api;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Characterization tests for GET /api/offers
 *
 * These tests document and lock the CURRENT behaviour of the public API
 * before any refactoring. Do not change these tests without understanding
 * the impact on the public contract.
 */
class GetOffersTest extends TestCase
{
    use RefreshDatabase;

    // ── Offer visibility ─────────────────────────────────────────────────────

    public function test_published_offers_are_returned(): void
    {
        Offer::factory()->published()->create();

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function test_draft_offers_are_not_returned(): void
    {
        Offer::factory()->draft()->create();

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_hidden_offers_are_not_returned(): void
    {
        Offer::factory()->hidden()->create();

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonCount(0);
    }

    public function test_only_published_offers_are_returned_when_mixed_states_exist(): void
    {
        Offer::factory()->published()->count(2)->create();
        Offer::factory()->draft()->create();
        Offer::factory()->hidden()->create();

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonCount(2);
    }

    // ── Product visibility within a published offer ───────────────────────────

    public function test_published_products_are_included_in_published_offer(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->published()->count(2)->create(['offer_id' => $offer->id]);

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonPath('0.products', fn ($products): bool => count($products) === 2);
    }

    public function test_draft_products_are_excluded_from_published_offer(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->draft()->create(['offer_id' => $offer->id]);

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonPath('0.products', []);
    }

    public function test_invisible_products_are_excluded_from_published_offer(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->invisible()->create(['offer_id' => $offer->id]);

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonPath('0.products', []);
    }

    public function test_only_published_products_are_returned_when_mixed_states_exist(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->published()->count(2)->create(['offer_id' => $offer->id]);
        Product::factory()->draft()->create(['offer_id' => $offer->id]);
        Product::factory()->invisible()->create(['offer_id' => $offer->id]);

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonPath('0.products', fn ($products): bool => count($products) === 2);
    }

    public function test_published_offer_with_no_published_products_returns_empty_product_list(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->draft()->count(2)->create(['offer_id' => $offer->id]);

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonPath('0.products', []);
    }

    // ── Response structure ────────────────────────────────────────────────────

    public function test_response_is_json(): void
    {
        $this->getJson('/api/offers')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json');
    }

    public function test_offer_contains_expected_fields(): void
    {
        Offer::factory()->published()->create();

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'name', 'slug', 'description', 'image', 'state', 'products'],
            ]);
    }

    public function test_product_contains_expected_fields(): void
    {
        $offer = Offer::factory()->published()->create();
        Product::factory()->published()->create(['offer_id' => $offer->id]);

        $this->getJson('/api/offers')
            ->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'products' => [
                        '*' => ['id', 'name', 'sku', 'image', 'price', 'state'],
                    ],
                ],
            ]);
    }

    public function test_endpoint_is_publicly_accessible_without_authentication(): void
    {
        $this->getJson('/api/offers')->assertOk();
    }

    public function test_returns_empty_array_when_no_published_offers_exist(): void
    {
        $this->getJson('/api/offers')
            ->assertOk()
            ->assertExactJson([]);
    }
}
