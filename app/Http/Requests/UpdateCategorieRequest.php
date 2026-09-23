<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategorieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Récupère la catégorie depuis l'URL /api/categories/{categorie}
        $categorieId = $this->route('categorie')?->id ?? $this->route('categorie');

        return [
            'nom' => [
                'sometimes',
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('categories', 'nom')->ignore($categorieId)
            ],
            'description' => 'nullable|string',
        ];
    }
}