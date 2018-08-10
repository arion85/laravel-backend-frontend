<?php

$return_array = [

    'name' => 'BackEnd',

    'providers' => [
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        Backend\Providers\AppServiceProvider::class,
        Backend\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        Backend\Providers\EventServiceProvider::class,
        Backend\Providers\RouteServiceProvider::class,

    ],

];

$file_name = basename(__FILE__);

$common_config = realpath(app()->basePath().'/../Common/config/'.$file_name);
if(is_file($common_config)){
    return array_merge_recursive($return_array, include ($common_config));
}

return $return_array;