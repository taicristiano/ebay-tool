<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SettingShipping;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return bool
     */
    public function update(User $user, SettingShipping $shipping)
    {
        return $user->isSuperAdmin() || $user->id === $shipping->user_id;
    }
}
