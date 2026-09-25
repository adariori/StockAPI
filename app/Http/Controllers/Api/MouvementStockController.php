<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\MouvementStockResource;
use App\Http\Requests\StoreMouvementRequest;
use Illuminate\Http\JsonResponse;

class MouvementStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $mouvements = MouvementStock::with(['user', 'produit'])->latest()->get();

        return MouvementStockResource::collection($mouvements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMouvementRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), [
            'user_id' => $request->user()->id,
        ]);

        $mouvement = MouvementStock::create($data);

        return response()->json([
            'message' => 'Mouvement de stock enregistré',
            'data'    => new MouvementStockResource($mouvement->load(['user', 'produit']))
        ], 201);
    }

    /**
     * Display the specified resource.
     */
   public function show(MouvementStock $mouvement): JsonResponse
    {
        return response()->json([
            'data' => new MouvementStockResource($mouvement->load(['user', 'produit']))
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
