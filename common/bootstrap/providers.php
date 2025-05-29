<?php

$prefix=ucfirst(app()->get('app.side'));

return [
    $prefix.'\\'.App\Providers\AppServiceProvider::class,
];
