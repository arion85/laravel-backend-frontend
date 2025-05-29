<?php

$prefix=ucfirst(app()->get('app.side'))??'Common';

return [
    $prefix.'\\'.App\Providers\AppServiceProvider::class,
];
