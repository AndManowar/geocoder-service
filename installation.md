## Установка

_Требования к окружению:_
* Nginx / Apache2
* Php >= 7.1
* Mysql >= 5.6
* Phalcon 3.1

Установка Composer
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```
Если у вас уже установлен Composer, обновите его при помощи:
```bash
composer self-update
```
После установки нужно выполнить команду
```bash
composer install
```

## Настройка
**Настройки тестового или боевого окружения**

В файле `public/index.php`
```php
//define('APP_ENV', 'local');
```
Если раскомментировать, тогда в `App/bootstrap.php` 
будет загружаться конфиг файл `App/config/config.php` и выполняться 
слияние конфигураций c `App/config/config.local.php`

**Настройки подключения к базе данных**

В файле: 
* `App/config/config.php` - если не используется APP_ENV
* `App/config/config.local.php` - если используется APP_ENV

```php
'database' => [
    'adapter'     => 'Mysql',
    'host'        => 'localhost',
    'username'    => 'root',
    'password'    => '123',
    'dbname'      => 'geocoder',
    'charset'     => 'utf8',
]
 ```

**Поднитие миграций базы данных (Phinx)**

Если в корне проекта нет файла `phinx.yml`, выполняем инициализацию
```bash
php vendor/bin/phinx init
```
После чего появиться файл `phinx.yml` в корне проекта, с таким содержимым:
```yaml
paths:
    migrations: '%%PHINX_CONFIG_DIR%%/db/migrations'
    seeds: '%%PHINX_CONFIG_DIR%%/db/seeds'
environments:
    default_migration_table: phinxlog
    default_database: development
    production:
        adapter: mysql
        host: localhost
        name: geocoder
        user: root
        pass: ''
        port: 3306
        charset: utf8
    development:
        adapter: mysql
        host: localhost
        name: geocoder
        user: root
        pass: ''
        port: 3306
        charset: utf8
    testing:
        adapter: mysql
        host: localhost
        name: geocoder
        user: root
        pass: ''
        port: 3306
        charset: utf8
version_order: creation
```

Выполняем настройку подключений к базе(ам) данных и после выполняем команду:
```bash
php vendor/bin/phinx migrate
```