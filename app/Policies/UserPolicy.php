<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Authorization;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * accept or block access user manager
     *
     * @param  User  $user
     * @return bool
     */
    public function userManager(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * accept or block access setting
     * @param  User   $user
     * @return bool
     */
    public function setting(User $user)
    {
        return $user->isSuperAdmin() || $user->isGuestAdmin();
    }

    /**
     * accept or block get yahoo auction info
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function yahooAuctionInfo(User $user)
    {
        return in_array(Authorization::YAHOO_AUCTION_INFO, $user->authorization);
    }

    /**
     * accept or block get amazon info
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function amazoneInfo(User $user)
    {
        return in_array(Authorization::AMAZONE_INFO, $user->authorization);
    }

    /**
     * accept or block monitoring product
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function monitoringProduct(User $user)
    {
        return in_array(Authorization::MONITORING_PRODUCT, $user->authorization);
    }

    /**
     * accept or block product manager
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function productManager(User $user)
    {
        return $user->isSuperAdmin() || $user->isGuestAdmin();
    }

    /**
     * accept or block order
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function order(User $user)
    {
        return $user->isSuperAdmin() || $user->isGuestAdmin();
    }

    /**
     * accept or block revenue
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function revenue(User $user)
    {
        return $user->isSuperAdmin() || $user->isGuestAdmin();
    }

    /**
     * accept or block post product
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function postProduct(User $user)
    {
        return $user->isSuperAdmin() || $user->isGuestAdmin();
    }
}
