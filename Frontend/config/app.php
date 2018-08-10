<?php

$return_array = [

    'name' => 'FrontEnd',

    'providers' => [

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        Frontend\Providers\AppServiceProvider::class,
        Frontend\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        Frontend\Providers\EventServiceProvider::class,
        Frontend\Providers\RouteServiceProvider::class,

    ],

];

$file_name = basename(__FILE__);

$common_config = realpath(app()->basePath().'/../Common/config/'.$file_name);
if(is_file($common_config)){
    return array_merge_recursive($return_array, include ($common_config));
}

return $return_array;
