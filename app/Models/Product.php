<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public static $states = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'invisible' => 'Invisible',
    ];

    protected $fillable = [
        'offer_id',
        'name',
        'sku',
        'image',
        'price',
        'state',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
