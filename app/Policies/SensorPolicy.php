<?php

namespace App\Policies;

use App\Models\Sensor;
use App\Models\UserInterface;
use Illuminate\Auth\Access\HandlesAuthorization;

class SensorPolicy
{
    use HandlesAuthorization;

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
        return $user->isAdmin()  ;
    }
    public function nurse_dashboard_logs(UserInterface $user)
    {
        return   $user->isNurse();
    }
    /**
     * Determine whether the user is head nurse.
     *
     * @param  \App\Models\UserInterface  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function head_nurse_index(UserInterface $user)
    {
        return $user->isHeadNurse();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserInterface $user, Sensor $sensor)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\UserInterface  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(UserInterface $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserInterface $user, Sensor $sensor)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserInterface $user, Sensor $sensor)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(UserInterface $user, Sensor $sensor)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\UserInterface  $user
     * @param  \App\Models\Sensor  $sensor
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(UserInterface $user, Sensor $sensor)
    {
        //
    }
}
