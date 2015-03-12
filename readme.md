## flk-honlap

A Fertőrákosi Lövészklub hivatalos oldalának forráskódja. 

[www.fertorakosi-loveszklub.hu](http://www.fertorakosi-loveszklub.hu)

### Licenc
A projekt [GNU General Public License, version 3](http://opensource.org/licenses/gpl-3.0.html) licenc alatt áll.

### License
The project is open-sourced software licensed under [GNU General Public License, version 3](http://opensource.org/licenses/gpl-3.0.html) license

## Installation
1. Install following:
  - Git
  - HTTP server (public version runs on nginx)
  - PHP 5.4+
  - MySQL
  - `php5-cli` for composer / artisan, `php5-mcrypt` for laravel
2. Get source code
  - `$ git clone https://github.com/fertorakosi-loveszklub/flk-honlap/`
3. Install [composer](https://getcomposer.org/download/) to project root
  - `$ curl -sS https://getcomposer.org/installer | php`
4. Update dependencies with composer
  - `$ php composer.phar update`
5. Edit config files
  - `app/config/app.php` - disable debug, edit website url
  - `app/config/database.php` - edit mysql connection settings
6. Config [SammyK's Laravel Facebook SDK](https://github.com/SammyK/LaravelFacebookSdk)
  - `$ php artisan config:publish sammyk/laravel-facebook-sdk`
  - Edit `app/config/packages/sammyk/laravel-facebook-sdk/config.php` (set Facebook app_id and app_secret)
7. Create database structure
  - `mysql -u user -p < create_tables.sql`
