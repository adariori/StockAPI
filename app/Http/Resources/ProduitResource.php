<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProduitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'nom'          => $this->nom,
            'description'  => $this->description,
            'prix'         => (float) $this->prix,
            'stock_actuel' => (int) $this->stock_actuel,
            'stock_min'    => (int) $this->stock_min,
            'stock_faible' => $this->stock_actuel <= $this->stock_min,
            'image_url'    => $this->image_path ? Storage::url($this->image_path) : null,
            
            'categorie_id' => $this->categorie_id,
            'categorie'    => new CategorieResource($this->whenLoaded('categorie')),
            
            'created_at'   => $this->created_at?->toIso8601String(),
        ];
    }
}