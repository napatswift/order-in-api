<?php

namespace App\Policies;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RatingPolicy
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
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Rating $rating)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_manager == 0 && $user->is_employee == 0
            ? Response::allow()
            : Response::denyWithStatus(401, 'You are not a customer');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Rating $rating)
    {
        return $user->is_manager == 0 && $user->is_employee == 0
            ? Response::allow()
            : Response::denyWithStatus(401, 'You are not a customer');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Rating $rating)
    {
        return $user->is_manager == 0 && $user->is_employee == 0
            ? Response::allow()
            : Response::denyWithStatus(401, 'You are not a customer');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Rating $rating)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rating  $rating
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Rating $rating)
    {
        //
    }
}
