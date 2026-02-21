<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Queries\CatalogueQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OfferController extends Controller
{
    public function index(CatalogueQuery $catalogueQuery): AnonymousResourceCollection
    {
        return OfferResource::collection($catalogueQuery->getPublishedOffers());
    }
}
