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
        Validator::extend('greaterThanOrEqualZero', function ($attribute, $value, $parameters, $validator) {
            return $value >= 0;
        });
        Validator::extend('greaterThanZero', function ($attribute, $value, $parameters, $validator) {
            if (strlen($value) > 0) {
                return $value > 0;
            }
            return true;
        });
        Validator::extend('materialQuantity', function ($attribute, $value, $parameters, $validator) {
            if (strlen($value) > 0) {
                if (!is_numeric($value)) {
                    return false;
                }
                return $value > 0;
            }
            return true;
        });
        Validator::extend('product_size', function ($attribute, $value, $parameters, $validator) {
            if (strlen($value) > 0) {
                $value = strtolower($value);
                $array = explode('x', $value);
                if (count($array) != 3) {
                    return false;
                }
                foreach ($array as $item) {
                    if (!is_numeric($item)) {
                        return false;
                    }
                    if ($item <= 0) {
                        return false;
                    }
                }
                return true;
            }
            return true;
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
