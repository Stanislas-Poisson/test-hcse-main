<?php

namespace Tests\Feature\BackOffice;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Characterization tests for back-office offer routes.
 *
 * Lock the current authentication requirements before refactoring.
 */
class OfferAccessTest extends TestCase
{
    use RefreshDatabase;

    // ── Guest access is blocked ───────────────────────────────────────────────

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_offer_create(): void
    {
        $this->get('/offers/create')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_offer_edit(): void
    {
        $offer = Offer::factory()->create();

        $this->get("/offers/{$offer->id}/edit")->assertRedirect('/login');
    }

    public function test_guest_cannot_delete_offer(): void
    {
        $offer = Offer::factory()->create();

        $this->delete("/offers/{$offer->id}")->assertRedirect('/login');
    }

    // ── Authenticated access works ────────────────────────────────────────────

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_authenticated_user_can_access_offer_create(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/offers/create')->assertOk();
    }

    public function test_authenticated_user_can_access_offer_edit(): void
    {
        $user = User::factory()->create();
        $offer = Offer::factory()->create();

        $this->actingAs($user)->get("/offers/{$offer->id}/edit")->assertOk();
    }

    public function test_authenticated_user_can_see_all_offer_states_in_dashboard(): void
    {
        $user = User::factory()->create();
        Offer::factory()->published()->create();
        Offer::factory()->draft()->create();
        Offer::factory()->hidden()->create();

        $testResponse = $this->actingAs($user)->get('/dashboard');

        $testResponse->assertOk();
        $testResponse->assertViewHas('offers', fn ($offers): bool => $offers->count() === 3);
    }
}
