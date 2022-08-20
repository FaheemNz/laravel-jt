<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

/**
 * Custom Rules for Validation
 * 
 */
class CustomRulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Validator::extend('c_text', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^([a-zA-Z0-9\s\/&_\-\[\]\$,.!#)(\@\:]*)$/u', $value);
        }, 'The input you provided is not in correct format');
        
        Validator::extend('c_name', function($attribute, $value, $parameters, $validator) {
            return preg_match('/^[a-zA-Z0-9\s\/&_\-\@\$]$/u', $value);
        }, 'The input you provided is not in correct format');
    }
}
