<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

$host = explode('.', $_SERVER['HTTP_HOST']);
$is_admin = (array_shift($host)=='admin')? true : false;

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.

if ($is_admin){
    $backend_path = dirname(__DIR__);
    if ($uri !== '/' && file_exists($backend_path . '/Backend/public' .$uri)) {
        $accept_headers = explode(',',$_SERVER['HTTP_ACCEPT']);
        $type = array_shift($accept_headers);

        header ("HTTP/1.0 200 Ok");
        if($type!=='*/*'){
            header("Content-Type: $type");
        }else{
            header("Content-Type: ");
        }

        include $backend_path . '/Backend/public' .$uri;
        exit(null);
    }
    require_once $backend_path . '/Backend/public/index.php';
}else{
    if ($uri !== '/' && file_exists(__DIR__ . '/public' .$uri)) {
        return false;
    }
    require_once __DIR__ . '/public/index.php';
}
