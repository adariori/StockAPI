<?php

namespace Database\Factories;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->words(3, true),
            'description' => fake()->paragraphs(2, true),
            'prix' => fake()->randomFloat(2, 10, 1000),
            'stock_actuel' => fake()->numberBetween(0, 100),
            'stock_min' => fake()->numberBetween(5, 20),
            'image_path' => fake()->imageUrl(640, 480, 'products', true),
            'categorie_id' => Categorie::factory(),
        ];
    }
}
