<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('required_if_in_array', function ($attribute, $value, $parameters, $validator)
        {
            $validator_data = $validator->getData();
            if(!is_array($validator_data[$parameters[0]])) {
                $validator->errors()->add($attribute, ':parameters[0] field must be an array.');
            }
            foreach($validator_data[$parameters[0]] as $value_array) {
                if(!is_string($value_array)) {
                    if($value_array == $parameters[1]) {
                        if(empty($value)) {
                            $validator->errors()->add($attribute, 'The '. $attribute .' field is required if ' . $parameters[0] . ' array has a value of ' . $parameters[1] . '.');                            
                            break;
                        }
                    }
                } else {
                    if($value_array === $parameters[1]) {
                        if(empty($value)) {
                            $validator->errors()->add($attribute, 'The '. $attribute .' field is required if ' . $parameters[0] . ' array has a value of ' . $parameters[1] . '.');                          
                            break;
                        }
                    }
                }
            }
            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
