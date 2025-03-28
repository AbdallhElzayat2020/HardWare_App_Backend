<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserInterface;
use App\Models\Ward;
use Illuminate\Auth\Access\HandlesAuthorization;

class WardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ward $ward)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserInterface $user, Ward $ward)
    {
        if ($user->isSuperAdmin()) {
            return false;
        }
        return $ward->hospital->id === $user->hospital->id && $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ward $ward)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Ward $ward)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Ward $ward)
    {
        //
    }
}
