<?php

namespace Database\Factories;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MouvementStock>
 */
class MouvementStockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['entree', 'sortie']),
            'quantite' => fake()->numberBetween(1, 50),
            'motif' => fake()->randomElement([
                'Achat fournisseur',
                'Vente client',
                'Ajustement d\'inventaire',
                'Produit endommagé',
                'Retour client',
            ]),
            'user_id' => User::factory(),
            'produit_id' => Produit::factory(),
        ];
    }
}
