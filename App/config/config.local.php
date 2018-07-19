<?php
/**
 * Конфиг для окружения  local
 * Применяеться если  define('APP_ENV', 'local');
 */
return [
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'asdfasdf1',
        'dbname'      => 'geocoder',
        'charset'     => 'utf8',
    ],
];