<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Produit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'stock_actuel',
        'stock_min',
        'image_path',
        'categorie_id',
    ];

    /**
     * Scope pour filtrer les produits sous le seuil d'alerte
     */
    public function scopeStockFaible(Builder $query): Builder
    {
        return $query->whereColumn('stock_actuel', '<=', 'stock_min');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }
}