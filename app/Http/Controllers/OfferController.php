<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function create()
    {
        return view('offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:offers,slug'],
            'image' => ['required', 'image'],
            'description' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'in:draft,published,hidden'],
        ]);

        Offer::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $request->image->store('offers', ['disk' => 'public']),
            'description' => $request->description,
            'state' => $request->state,
        ]);

        return redirect()->route('dashboard');
    }

    public function edit($offerId)
    {
        return view('offers.edit', [
            'offer' => Offer::find($offerId),
        ]);
    }

    public function update(Request $request, $offerId)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'image' => ['required', 'file'],
            'description' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'in:draft,published,hidden'],
        ]);

        Offer::find($offerId)->update($request->all('name', 'slug', 'description', 'state'));

        if ($request->hasFile('image')) {
            Offer::find($offerId)->update(['image' => $request->file('image')->store('offers', ['disk' => 'public'])]);
        }

        return redirect()->route('dashboard');
    }

    public function destroy($offerId)
    {
        Offer::where('id', $offerId)->delete();

        return redirect()->route('dashboard');
    }

    public function show(string $offerId)
    {
        $offer = Offer::with('products')->findOrFail($offerId);

        return view('offers.show', compact('offer'));
    }
}
