<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    static $states = [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'hidden' => 'Masqué',
    ];

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'state',
    ];

    public function scopeOfState($query, $state)
    {
        return $query->where('state', $state);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
