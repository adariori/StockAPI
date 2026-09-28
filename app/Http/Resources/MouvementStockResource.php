<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MouvementStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'quantite'   => $this->quantite,
            'motif'      => $this->motif,
            'user'       => $this->whenLoaded('user', function () {
                return [
                    'id'    => $this->user->id,
                    'name'  => $this->user->name,
                    'email' => $this->user->email,
                    'role'  => $this->user->role,
                ];
            }),
            'produit'    => new ProduitResource($this->whenLoaded('produit')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}