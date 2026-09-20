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
        // 1. Crée les catégories et leurs produits à partir du catalogue de la boutique
        foreach ($this->catalogue() as $donneesCategorie) {
            $categorie = Categorie::create([
                'nom' => $donneesCategorie['nom'],
                'description' => $donneesCategorie['description'],
            ]);

            $categorie->produits()->createMany($donneesCategorie['produits']);
        }

        // 2. Récupère des utilisateurs existants (ou en crée s'il n'y en a aucun)
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory(3)->create();
        }

        // 3. Génère 50 mouvements de stock associés aux produits et utilisateurs
        MouvementStock::factory(50)
            ->recycle(Produit::all())
            ->recycle($users)
            ->create();
    }

    /**
     * Catalogue d'une boutique locale, prix en FCFA.
     * Plusieurs produits sont volontairement à ou sous leur stock minimum.
     *
     * @return list<array{
     *     nom: string,
     *     description: string,
     *     produits: list<array{nom: string, description: string, prix: int, stock_actuel: int, stock_min: int}>
     * }>
     */
    private function catalogue(): array
    {
        return [
            [
                'nom' => 'Alimentation',
                'description' => 'Denrées de base et produits d\'épicerie.',
                'produits' => [
                    ['nom' => 'Riz parfumé 5 kg', 'description' => 'Sac de riz parfumé à grains longs.', 'prix' => 4500, 'stock_actuel' => 60, 'stock_min' => 15],
                    ['nom' => 'Huile de palme 1 L', 'description' => 'Huile de palme rouge en bouteille.', 'prix' => 1200, 'stock_actuel' => 40, 'stock_min' => 10],
                    ['nom' => 'Spaghetti 500 g', 'description' => 'Pâtes alimentaires de blé dur.', 'prix' => 500, 'stock_actuel' => 80, 'stock_min' => 20],
                    ['nom' => 'Sucre en poudre 1 kg', 'description' => 'Sucre blanc cristallisé.', 'prix' => 900, 'stock_actuel' => 8, 'stock_min' => 15],
                ],
            ],
            [
                'nom' => 'Boissons',
                'description' => 'Eaux, jus et boissons gazeuses.',
                'produits' => [
                    ['nom' => 'Eau minérale 1,5 L', 'description' => 'Bouteille d\'eau minérale plate.', 'prix' => 400, 'stock_actuel' => 120, 'stock_min' => 30],
                    ['nom' => 'Jus d\'orange 1 L', 'description' => 'Jus d\'orange 100 % pur jus.', 'prix' => 1000, 'stock_actuel' => 35, 'stock_min' => 10],
                    ['nom' => 'Soda cola 33 cl', 'description' => 'Canette de boisson gazeuse au cola.', 'prix' => 350, 'stock_actuel' => 90, 'stock_min' => 24],
                    ['nom' => 'Bière blonde 65 cl', 'description' => 'Bouteille de bière blonde locale.', 'prix' => 800, 'stock_actuel' => 12, 'stock_min' => 12],
                ],
            ],
            [
                'nom' => 'Hygiène et beauté',
                'description' => 'Produits de soin et d\'hygiène personnelle.',
                'produits' => [
                    ['nom' => 'Savon de toilette', 'description' => 'Savon parfumé en pain de 125 g.', 'prix' => 300, 'stock_actuel' => 100, 'stock_min' => 25],
                    ['nom' => 'Dentifrice 75 ml', 'description' => 'Dentifrice au fluor, tube de 75 ml.', 'prix' => 800, 'stock_actuel' => 45, 'stock_min' => 12],
                    ['nom' => 'Papier toilette (lot de 4)', 'description' => 'Lot de 4 rouleaux double épaisseur.', 'prix' => 1000, 'stock_actuel' => 50, 'stock_min' => 15],
                    ['nom' => 'Shampooing 250 ml', 'description' => 'Shampooing pour tous types de cheveux.', 'prix' => 1500, 'stock_actuel' => 25, 'stock_min' => 8],
                ],
            ],
            [
                'nom' => 'Entretien',
                'description' => 'Produits de nettoyage et d\'entretien de la maison.',
                'produits' => [
                    ['nom' => 'Lessive en poudre 1 kg', 'description' => 'Lessive en poudre pour lavage à la main ou en machine.', 'prix' => 1800, 'stock_actuel' => 6, 'stock_min' => 10],
                    ['nom' => 'Liquide vaisselle 500 ml', 'description' => 'Détergent liquide dégraissant.', 'prix' => 700, 'stock_actuel' => 30, 'stock_min' => 10],
                    ['nom' => 'Eau de Javel 1 L', 'description' => 'Désinfectant concentré pour sols et sanitaires.', 'prix' => 600, 'stock_actuel' => 42, 'stock_min' => 12],
                    ['nom' => 'Éponges (lot de 3)', 'description' => 'Lot de 3 éponges à récurer.', 'prix' => 500, 'stock_actuel' => 55, 'stock_min' => 15],
                ],
            ],
            [
                'nom' => 'Papeterie',
                'description' => 'Fournitures scolaires et de bureau.',
                'produits' => [
                    ['nom' => 'Cahier 96 pages', 'description' => 'Cahier à couverture souple, grands carreaux.', 'prix' => 300, 'stock_actuel' => 150, 'stock_min' => 40],
                    ['nom' => 'Stylos bille bleus (boîte de 10)', 'description' => 'Boîte de 10 stylos à bille pointe moyenne.', 'prix' => 1000, 'stock_actuel' => 28, 'stock_min' => 8],
                    ['nom' => 'Ruban adhésif', 'description' => 'Rouleau de ruban adhésif transparent.', 'prix' => 350, 'stock_actuel' => 5, 'stock_min' => 10],
                    ['nom' => 'Rame de papier A4', 'description' => 'Rame de 500 feuilles A4 80 g.', 'prix' => 3500, 'stock_actuel' => 20, 'stock_min' => 5],
                ],
            ],
        ];
    }
}
