<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function create(): View
    {
        return view('offers.create');
    }

    public function store(StoreOfferRequest $storeOfferRequest): RedirectResponse
    {
        $data = $storeOfferRequest->validated();
        $data['image'] = $storeOfferRequest->file('image')->store('offers', ['disk' => 'public']);

        Offer::create($data);

        return to_route('dashboard');
    }

    public function edit(string $offerId): View
    {
        return view('offers.edit', [
            'offer' => Offer::findOrFail($offerId),
        ]);
    }

    public function update(UpdateOfferRequest $updateOfferRequest, string $offerId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);

        $offer->update($updateOfferRequest->only('name', 'slug', 'description', 'state'));

        if ($updateOfferRequest->hasFile('image')) {
            $offer->update(['image' => $updateOfferRequest->file('image')->store('offers', ['disk' => 'public'])]);
        }

        return to_route('dashboard');
    }

    public function destroy(string $offerId): RedirectResponse
    {
        Offer::findOrFail($offerId)->delete();

        return to_route('dashboard');
    }

    public function show(string $offerId): View
    {
        $offer = Offer::with('products')->findOrFail($offerId);

        return view('offers.show', ['offer' => $offer]);
    }
}
