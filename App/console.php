<?php
/********************************
 * Created by GoldenEye.
 * copyright 2010 - 2018
 ********************************/

use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Di\FactoryDefault\Cli as Console;
use Phalcon\Loader;

define('BASE_PATH', __DIR__ . '/../');
define('APP_PATH', BASE_PATH . '/App');

// Using the CLI factory default services container
$di = new Console();

/**
 * Register the autoloader and tell it to register the tasks directory
 */
$loader = new Loader();

$loader->registerDirs([
    __DIR__ . "/Tasks"
]);

$loader->registerNamespaces([
    'App' => APP_PATH . '/',
])->register();

$loader->register();

// Load the configuration file
$di->setShared('config', function () {
    // Load Global Settings
    $confCollection = include APP_PATH . '/config/config.php';
    $config = new \Phalcon\Config($confCollection);
    return $config;
});

/**
 * Компонент БД
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

// Create a console application
$console = new ConsoleApp();

$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (Exception $e) {
    echo $e->getMessage();

    exit(255);
}
