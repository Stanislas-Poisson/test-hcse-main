<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Seeds offers and products with varied states to demonstrate all visibility cases.
 *
 * Visibility matrix:
 * - Published offer + published products  → visible in public API (with products)
 * - Published offer + mixed products      → visible in API, only published products shown
 * - Draft offer + any products            → hidden from public API
 * - Hidden offer + any products           → hidden from public API
 */
class OfferSeeder extends Seeder
{
    public function run(): void
    {
        // Case 1: Published offer with all products published → fully visible
        Offer::factory()
            ->published()
            ->has(Product::factory()->published()->count(3))
            ->create(['name' => 'Pack Vacances Été', 'slug' => 'pack-vacances-ete']);

        // Case 2: Published offer with mixed product states → partially visible
        $mixedOffer = Offer::factory()
            ->published()
            ->create(['name' => 'Pack Sport Premium', 'slug' => 'pack-sport-premium']);

        Product::factory()->published()->create(['offer_id' => $mixedOffer->id, 'name' => 'Abonnement salle']);
        Product::factory()->invisible()->create(['offer_id' => $mixedOffer->id, 'name' => 'Option vestiaire']);
        Product::factory()->draft()->create(['offer_id' => $mixedOffer->id, 'name' => 'Cours collectifs']);

        // Case 3: Published offer with no published products → visible but empty product list
        Offer::factory()
            ->published()
            ->has(Product::factory()->draft()->count(2))
            ->create(['name' => 'Pack Bien-être', 'slug' => 'pack-bien-etre']);

        // Case 4: Draft offer → never visible in public API
        Offer::factory()
            ->draft()
            ->has(Product::factory()->published()->count(2))
            ->create(['name' => 'Pack Hiver [DRAFT]', 'slug' => 'pack-hiver-draft']);

        // Case 5: Hidden offer → never visible in public API
        Offer::factory()
            ->hidden()
            ->has(Product::factory()->published()->count(2))
            ->create(['name' => 'Pack Ancien [HIDDEN]', 'slug' => 'pack-ancien-hidden']);

        // Additional published offers with random products for realistic volume
        Offer::factory()
            ->published()
            ->count(3)
            ->create()
            ->each(fn (Offer $offer) => Product::factory()
                ->published()
                ->count(fake()->numberBetween(2, 5))
                ->create(['offer_id' => $offer->id]));
    }
}
