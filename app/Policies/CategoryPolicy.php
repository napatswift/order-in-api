<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Category $category)
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
            $restaurant = $manager->restaurant;
            if (is_null($restaurant)) {
                return Response::deny('You don\'t have a restaurant');
            }
            return Response::allow();
        }

        return Response::denyWithStatus(401, 'You are not a manager');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Category $category)
    {
        if ($user->is_manager) {
            $manager = Manager::findOrFail($user->id);
            $restaurant_id = $manager->restaurant->id;
            return $restaurant_id == $category->restaurant_id
                ? Response::allow()
                : Response::deny();
        }

        return Response::denyWithStatus(401, 'You are not manager');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Category $category)
    {
        if ($user->is_manager) {
            $manager = Manager::findOrFail($user->id);
            $restaurant_id = $manager->restaurant->id;
            return $restaurant_id == $category->restaurant_id
                ? Response::allow()
                : Response::deny();
        }

        return Response::denyWithStatus(401, 'You are not manager');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Category $category)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Category $category)
    {
        return Response::deny();
    }
}