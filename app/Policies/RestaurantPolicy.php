<?php

namespace App\Policies;

use App\Models\Manager;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RestaurantPolicy
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
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Restaurant $restaurant)
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
        if ($user->is_manager) {
            $manager = Manager::findOrFail($user->id);
            return is_null($manager->restaurant)
                ? Response::allow()
                : Response::deny('You already have a restaurant');
        }

        return Response::denyWithStatus(401, 'You are not manager, not allow to create restaurant');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Restaurant $restaurant)
    {
        if ($user->is_manager) {
            $manager = Manager::findOrFail($user->id);
            if (is_null($manager->restaurant)) {
                return Response::deny('You don\'t have a restaurant');
            }
            if ($manager->restaurant->id == $restaurant->id) {
                return Response::allow();
            }
            return Response::deny('You are not allowed to modify this restaurant');
        }

        return Response::denyWithStatus(401, 'You are not manager');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Restaurant $restaurant)
    {
        if ($user->is_manager) {
            $manager = Manager::findOrFail($user->id);
            if (is_null($manager->restaurant)) {
                return Response::deny('You don\'t have a restaurant');
            }
            if ($manager->restaurant->id == $restaurant->id) {
                return Response::allow();
            }
            return Response::deny('You are not allowed to modify this restaurant');
        }

        return Response::denyWithStatus(401, 'You are not manager');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Restaurant $restaurant)
    {
        if ($user->is_manager) {
            $manager = Manager::findOrFail($user->id);
            if (is_null($manager->restaurant)) {
                return Response::deny('You don\'t have a restaurant');
            }
            if ($manager->restaurant->id == $restaurant->id) {
                return Response::allow();
            }
            return Response::deny('You are not allowed to modify this restaurant');
        }

        return Response::denyWithStatus(401, 'You are not manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Restaurant $restaurant)
    {
        return Response::deny();
    }
}
