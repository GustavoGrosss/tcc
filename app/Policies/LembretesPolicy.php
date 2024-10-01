<?php

namespace App\Policies;

use App\Models\Lembretes;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LembretesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
//        return $user->hasPermissionTo('lembrete_read');
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lembretes $lembretes): bool
    {
//        return $user->hasPermissionTo('lembrete_read');
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
//        return $user->hasPermissionTo('lembrete_create');
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lembretes $lembretes): bool
    {
//        return $user->hasPermissionTo('lembrete_update');
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lembretes $lembretes): bool
    {
//        return $user->hasPermissionTo('lembrete_delete');
        return true;
    }

}
