|Русский|English<br/>(Google Translator)  |
|---|----|
| * **Назначение папок** | * **About My structure** |
|**/Backend** папка для админ. части приложения<br/>**/Frontend** папка для сайта<br/>**/Common** папка для общих файлов, например, моделей, конфигурации и т.п.|**/Backend** folder for backend part<br/>**/Frontend** folder for frontend part<br/>**/Common** for general files, such as models, etc.|
| Структура папок в **/Frontend** и **/Backend** соответствуют структуре стандартного приложения laravel | The structure of folders in **/Frontend** and **/Backend** correspond to the structure of the standard applications laravel |
|* **Использование команды ARTISAN**|* **Using the ARTISAN command**|
| Для работы с _Backend_ необходимо запускать **admartisan**<br/>Для остальных случаев необходимо использовать **artisan** | To work with _Backend_ you need to run **admartisan** <br/> For other cases, you must use **artisan** |
|* **Настройка конфигурации**|* **Configuration setting**|
| 1. В папке **/Common/config** находятся общие настройки для _Backend_ и _Frontend_ частей<br/>2. Что бы разделить настройки, нужно удалить необходимый ключ из **/Common/config/\*.php** и добавить его в **/{_Frontend_ или _Backend_}/config/\*.php** | 1. In the **/Common/config** folder there are general settings for _Backend_ and _Frontend_ parts <br/> 2. To separate the settings, you need to remove the necessary key from **/Common/config/\*.php** and add it to **/{_Frontend_ or _Backend_}/config/\*.php** |
|* **Настройки сервера**|* **Server settings**|
| 1. Основной сайт - _site.dom_ => **/Frontend/public/**<br/>2. Административная часть - _admin.site.dom_ => **/Backend/public/** | 1. For Frontend part - _site.dom_ => **/Frontend/public/** <br/> 2.For Backend part - _admin.site.dom_ => **/Backend/public/** |
|* **Использование встроенного сервера Laravel**|* **Using the built-in server Laravel**|
| 1. Запустить **php artisan serve**<br/> 2. Добавить в файл /hosts строку **"127.0.0.1 admin.127.0.0.1"** (без ковычек)<br/>3. URL основного сайта **http://127.0.0.1:8000**<br/>4. URL Административной части - **http://admin.127.0.0.1:8000** | 1. Run command **php artisan serve** <br/>2. Add the line **"127.0.0.1 admin.127.0.0.1"** (without quotes) to the your /hosts file<br/>3. URL for _Frontend_ **http://127.0.0.1:8000** <br/> 4. URL for _Backend_- **http://admin.127.0.0.1:8000** |


#Установка / INSTALL

1. **git clone** https://github.com/arion85/laravel-backend-frontend.git _mySite_
2. **cd** _mySite_
3. run -> **composer install**
4. run -> **php -r "file_exists('.env') || copy('.env.example', 'Frontend/.env');"**
5. run -> **php -r "file_exists('.env') || copy('.env.example', 'Backend/.env');"**
6. run -> **php artisan key:generate**
7. run -> **php admartisan key:generate**
<br/>
<br/>
<br/>
<hr/>
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>
<br/>

##Learning Laravel<br/>
Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.<br/>
If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
