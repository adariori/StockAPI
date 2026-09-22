<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProduitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $produitId = $this->route('produit')?->id ?? $this->route('produit');

        return [
            'nom'          => ['sometimes', 'required', 'string', 'min:3', 'max:255', Rule::unique('produits')->ignore($produitId)],
            'description'  => 'required', 
            'prix'         => 'required|numeric|min:0', 
            'stock_actuel' => 'required|integer|min:0',
            'stock_min'    => 'required|integer|min:0', 
            'image_path'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
            'categorie_id' => 'required|exists:categories,id',
        ];
    }
}
