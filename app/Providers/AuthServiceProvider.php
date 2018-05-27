<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\SettingShipping' => 'App\Policies\ShippingPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // define gates
        Gate::define('user_manager', 'App\Policies\UserPolicy@userManager');
        Gate::define('yahoo_auction_info', 'App\Policies\UserPolicy@yahooAuctionInfo');
        Gate::define('amazone_info', 'App\Policies\UserPolicy@amazoneInfo');
        Gate::define('product_manager', 'App\Policies\UserPolicy@productManager');
        Gate::define('monitoring_product', 'App\Policies\UserPolicy@monitoringProduct');
        Gate::define('setting', 'App\Policies\UserPolicy@setting');
        Gate::define('order', 'App\Policies\UserPolicy@order');
        Gate::define('revenue', 'App\Policies\UserPolicy@revenue');
        Gate::define('post_product', 'App\Policies\UserPolicy@postProduct');
    }
}
