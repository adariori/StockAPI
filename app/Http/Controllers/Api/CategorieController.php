<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Resources\CategorieResource;
use Illuminate\Http\JsonResponse;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategorieResource::collection(Categorie::with('user')->latest(0)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategorieRequest $request)
    {
        Categorie::create($request->validated());
        return response()->json(new CategorieResource('categories.index'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, Categorie $categorie)
    {
        $categorie->update($request->validated());
        return redirect()->route('categories.show', $categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie): JsonResponse
    {
        $categorie->delete();
        return response()->json(null, 204);
    }
}
