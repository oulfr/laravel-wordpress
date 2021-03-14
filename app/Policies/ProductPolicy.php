<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param \App\Models\User $user
     * @param string $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        //dd($user);
        //if ($user->isAdmin()) {
            return true;
        //}
    }

    /**
     * Determine whether the user can view any products.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param App\Models\User $user
     * @param App\Models\Product $product
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
        //return $user->ID === $product->author->ID;
    }

    /**
     * Determine whether the user can create products.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param App\Models\User $user
     * @param App\Models\Product $product
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        //
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param App\Models\User $user
     * @param App\Models\Product $product
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        //
    }
}
