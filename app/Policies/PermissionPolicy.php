<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
//        return $user->hasPermissionTo('permission_read');
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return Response|bool
     */
    public function view(User $user, Permission $permission)
    {
//        return $user->hasPermissionTo('permission_read');
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
//        return $user->hasPermissionTo('permission_create');
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return Response|bool
     */
    public function update(User $user, Permission $permission)
    {
//        return $user->hasPermissionTo('permission_update');
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Permission $permission
     * @return Response|bool
     */
    public function delete(User $user, Permission $permission)
    {
//        return $user->hasPermissionTo('permission_delete');
        return true;
    }


}
