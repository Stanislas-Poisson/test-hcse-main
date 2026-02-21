<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    public function index(): JsonResponse
    {
        $offers = Offer::ofState('published')->with('products', fn ($q) => $q->where('state', 'published'))->get();

        return response()->json($offers);
    }
}
