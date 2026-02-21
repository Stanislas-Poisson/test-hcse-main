<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(string $offerId): View
    {
        $offer = Offer::findOrFail($offerId);
        $products = $offer->products()->latest()->get();

        return view('products.index', ['offer' => $offer, 'products' => $products]);
    }

    public function create(string $offerId): View
    {
        $offer = Offer::findOrFail($offerId);
        $product = new Product;

        return view('products.create', ['offer' => $offer, 'product' => $product]);
    }

    public function store(StoreProductRequest $storeProductRequest, string $offerId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);
        $product = new Product($storeProductRequest->validated());

        /** @phpstan-ignore assign.propertyType */
        $product->offer_id = $offer->id;
        /** @var UploadedFile $imageFile */
        $imageFile = $storeProductRequest->file('image');
        $imagePath = $imageFile->store('products', ['disk' => 'public']);
        assert(is_string($imagePath), 'Image storage failed');
        $product->image = $imagePath;
        $product->save();

        return to_route('offers.products.index', $offer->id)
            ->with('status', 'Produit créé avec succès.');
    }

    public function edit(string $offerId, string $productId): View
    {
        $offer = Offer::findOrFail($offerId);
        $product = $offer->products()->findOrFail($productId);

        return view('products.edit', ['offer' => $offer, 'product' => $product]);
    }

    public function update(UpdateProductRequest $updateProductRequest, string $offerId, string $productId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);
        $product = $offer->products()->findOrFail($productId);

        $product->update($updateProductRequest->validated());

        if ($updateProductRequest->hasFile('image')) {
            $product->update(['image' => $updateProductRequest->file('image')->store('products', ['disk' => 'public'])]);
        }

        return to_route('offers.products.index', $offer->id)
            ->with('status', 'Produit mis à jour avec succès.');
    }

    public function destroy(string $offerId, string $productId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);
        $product = $offer->products()->findOrFail($productId);
        $product->delete();

        return to_route('offers.products.index', $offer->id)
            ->with('status', 'Produit supprimé avec succès.');
    }
}
