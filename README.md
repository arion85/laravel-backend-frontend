<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<-- Sorry for mistakes (using Google Translated) -->
</p>

<p align="center">
Laravel Backend / Fronted Structure
</p>

<p align="center" style="font-weight: bold; color:red;"> !!Please, DON'T upgrade previous version from current Master branch!!</p>

## Install

1. Clone this repository to your local machine.
2. Once the repository is cloned, navigate into the project directory (using the cd command on your cmd or terminal).
3. Install the project dependencies using Composer. In the project directory, run the composer install on your cmd or terminal.
4. Copy .env.example file to .env on the root folder.
5. Generate a new application key by running command php artisan key:generate
6. Configure APP_ADMIN_PREFIXURL in .env file. It's subdomain for Backend starting. For example, APP_ADMIN_PREFIXURL=admin => http://admin.site.com

## Note
1. "Common" directory contains common files (configs, service providers, etc.) for the Frontend and Backend sides of the site.
2. You can also define your own necessary components for each side of the site separately. For an example, see frontend{or backend}/config/liveware.php.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
