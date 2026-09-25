<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMouvementRequest;
use App\Http\Resources\MouvementStockResource;
use App\Models\MouvementStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * Créer un mouvement.
     */
    public function store(StoreMouvementRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), [
            'user_id' => $request->user()->id,
        ]);

        $mouvement = MouvementStock::create($data);

        return response()->json([
            'message' => 'Mouvement de stock enregistré.',
            'data' => new MouvementStockResource($mouvement->load(['user', 'produit'])),
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
