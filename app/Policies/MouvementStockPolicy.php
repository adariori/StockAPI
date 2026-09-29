<?php

namespace App\Policies;

use App\Models\MouvementStock;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MouvementStockPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;  
        }
        return null;     
    }
    
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MouvementStock $mouvementStock): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, string $typeMouvement): bool
    {
        if ($user->role === 'employe' && $typeMouvement === 'entree') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MouvementStock $mouvementStock): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MouvementStock $mouvementStock): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MouvementStock $mouvementStock): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MouvementStock $mouvementStock): bool
    {
        return false;
    }
}
