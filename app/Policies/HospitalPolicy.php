<?php

namespace App\Policies;

use App\Models\Hospital;
use App\Models\User;
use App\Models\UserInterface;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class HospitalPolicy
{
    use HandlesAuthorization;


    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(UserInterface $user, $ability)
    {

        if ($user->isSuperAdmin()) {

            return true;
        }

    }


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\UserInterface  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(UserInterface $user)
    {
        return  false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserInterface $user, Hospital $hospital)
    {

        return   $user->hospital_id === $hospital->id;
    }
    

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\UserInterface  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(UserInterface $user)
    {

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserInterface $user, Hospital $hospital)
    {

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserInterface $user, Hospital $hospital)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(UserInterface $user, Hospital $hospital)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Hospital  $hospital
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(UserInterface $user, Hospital $hospital)
    {
        return false;
    }
}
