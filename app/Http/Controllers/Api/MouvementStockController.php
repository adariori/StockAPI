<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMouvementRequest;
use App\Http\Resources\MouvementStockResource;
use App\Models\MouvementStock;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MouvementStockController extends Controller
{
    /**
     * Liste paginée des mouvements avec filtres.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $mouvements = MouvementStock::query()
            ->with(['user', 'produit'])
            ->when($request->filled('produit_id'), function ($query) use ($request) {
                $query->where('produit_id', $request->integer('produit_id'));
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->string('type')->toString());
            })
            ->when($request->filled('date_debut'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->string('date_debut')->toString());
            })
            ->when($request->filled('date_fin'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->string('date_fin')->toString());
            })
            ->latest()
            ->paginate(15);

        return MouvementStockResource::collection($mouvements);
    }

    /**
     * Créer un mouvement et mettre à jour le stock atomiquement.
     */
    public function store(StoreMouvementRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mouvement = DB::transaction(function () use ($validated, $request) {
            /** @var Produit $produit */
            $produit = Produit::query()
                ->lockForUpdate()
                ->findOrFail($validated['produit_id']);

            if ($validated['type'] === 'sortie' && $produit->stock_actuel < $validated['quantite']) {
                throw ValidationException::withMessages([
                    'quantite' => "Stock insuffisant. Stock disponible: {$produit->stock_actuel}.",
                ]);
            }

            if ($validated['type'] === 'entree') {
                $produit->increment('stock_actuel', $validated['quantite']);
            } else {
                $produit->decrement('stock_actuel', $validated['quantite']);
            }

            return MouvementStock::query()->create([
                ...$validated,
                'user_id' => $request->user()->id,
            ])->load(['user', 'produit']);
        });

        return response()->json([
            'message' => 'Mouvement de stock enregistré.',
            'data' => new MouvementStockResource($mouvement),
        ], 201);
    }

    /**
     * Afficher un mouvement spécifique.
     */
    public function show(MouvementStock $mouvement): JsonResponse
    {
        return response()->json([
            'data' => new MouvementStockResource($mouvement->load(['user', 'produit'])),
        ]);
    }
}
