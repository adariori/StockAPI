<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Liste paginée avec filtres (Catégorie & Stock Faible)
     */
    public function index(Request $request)
    {
        $produits = Produit::with('categorie')
            // Filtre par categorie_id si présent dans la requête (?categorie_id=X)
            ->when($request->filled('categorie_id'), function ($query) use ($request) {
                $query->where('categorie_id', $request->categorie_id);
            })
            // Filtre les produits sous le seuil si ?stock_faible=1
            ->when($request->boolean('stock_faible'), function ($query) {
                $query->stockFaible();
            })
            ->latest()
            ->paginate(10); // Pagination automatique (10 par page)

        return ProduitResource::collection($produits);
    }

    /**
     * Créer un produit avec gestion d'image
     */
    public function store(StoreProduitRequest $request)
    {
        $data = $request->validated();

        // Traitement du fichier image s'il est fourni
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('produits', 'public');
        }

        $produit = Produit::create($data);

        return response()->json([
            'message' => 'Produit créé avec succès',
            'data'    => new ProduitResource($produit->load('categorie'))
        ], 201);
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Produit $produit)
    {
        return new ProduitResource($produit->load('categorie'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(UpdateProduitRequest $request, Produit $produit)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Suppression de l'ancienne image si elle existe
            if ($produit->image_path && Storage::disk('public')->exists($produit->image_path)) {
                Storage::disk('public')->delete($produit->image_path);
            }
            $data['image_path'] = $request->file('image')->store('produits', 'public');
        }

        $produit->update($data);

        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'data'    => new ProduitResource($produit->load('categorie'))
        ], 200);
    }

    /**
     * Supprimer un produit (Soft Delete)
     */
    public function destroy(Produit $produit)
    {
        // La suppression utilise SoftDeletes (défini sur le modèle Produit)
        $produit->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ], 200);
    }
}