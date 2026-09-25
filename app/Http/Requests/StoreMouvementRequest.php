<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMouvementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['entree', 'sortie'])],
            'quantite' => ['required', 'integer', 'min:1'],
            'motif' => ['nullable', 'string', 'max:255'],
            'produit_id' => ['required', 'integer', Rule::exists('produits', 'id')->withoutTrashed()],
        ];
    }
}
