<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidateProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('percent', function ($attribute, $value, $parameters, $validator) {
            if (is_numeric($value) && $value >= 0 && $value <= 100) {
                if (filter_var($value, FILTER_VALIDATE_INT)) {
                    return true;
                } elseif (filter_var($value, FILTER_VALIDATE_FLOAT) && strlen(explode('.', $value)[1]) == 1) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        });
        Validator::extend('greateThanOrEqualZero', function ($attribute, $value, $parameters, $validator) {
            return $value >= 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
