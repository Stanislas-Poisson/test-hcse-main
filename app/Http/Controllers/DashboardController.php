<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show(Request $request)
    {
        if ($request->state) {
            $offers = Offer::ofState($request->state);
        } else {
            $offers = Offer::query();
        }

        if ($request->name) {
            $offers = $offers->where('name', 'like', "%{$request->name}%");
        }

        if ($request->slug) {
            $offers = $offers->where('slug', 'like', "%{$request->slug}%");
        }

        return view('dashboard', ['offers' => $offers->get()]);
    }
}
