<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Génère 5 catégories
        $categories = Categorie::factory(5)->create();

        // 2. Récupère des utilisateurs existants (ou en crée s'il n'y en a aucun)
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory(3)->create();
        }

        // 3. Génère 20 produits distribués dans les catégories créées
        $produits = Produit::factory(20)
            ->recycle($categories)
            ->create();

        // 4. Génère 50 mouvements de stock associés aux produits et utilisateurs
        MouvementStock::factory(50)
            ->recycle($produits)
            ->recycle($users)
            ->create();
    }
}
