<?php
/**
 * Загрузка сервисов в DI, приложение
 *
 */

use Phalcon\Db\Dialect\Mysql as SqlDialect;

/**
 * Компонент БД
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;

    $dialect = new SqlDialect();

    $dialect->registerCustomFunction(
        'MATCH_AGAINST',
        function (SqlDialect $dialect, $expression) {
            $arguments = $expression['arguments'];
            return sprintf(
                "MATCH(%s) AGAINST(%s IN BOOLEAN MODE)",
                $dialect->getSqlExpression($arguments[0]),
                $dialect->getSqlExpression($arguments[1])
            );
        }
    );

    $dialect->registerCustomFunction(
        'GROUP_CONCAT_SEPARATOR',
        function (SqlDialect $dialect, $expression) {
            $arguments = $expression['arguments'];
            return sprintf(
                "GROUP_CONCAT(DISTINCT %s SEPARATOR %s)",
                $dialect->getSqlExpression($arguments[0]),
                $dialect->getSqlExpression($arguments[1])
            );
        }
    );

    $params = [
        'host'         => $config->database->host,
        'username'     => $config->database->username,
        'password'     => $config->database->password,
        'dbname'       => $config->database->dbname,
        'charset'      => $config->database->charset,
        // Регаем новую функи для БД - MATCH_AGAINST и GROUP_CONCAT_SEPARATOR (нужна для поиска и фильтрации в админке)
        "dialectClass" => $dialect
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * Свой Компонент Response
 */
$di->setShared('response', new \Topnlab\PhalconBase\v2\Components\ApiResponse());

/**
 * Свой Компонент Request
 */
$di->setShared('request', new \Topnlab\PhalconBase\v2\Components\ApiRequest());

/**
 * Object Type manager
 */
$di->setShared('typeManager', function () {
    return new \App\Models\Managers\ObjectTypeManager();
});

/**
 * Object manager
 */
$di->setShared('objectManager', function () {
    return new \App\Models\Managers\ObjectManager();
});

/**
 * folkDistrictMappingManager manager
 */
$di->setShared('folkDistrictMappingManager', function () {
    return new \App\Models\Managers\FolkDistrictMappingManager();
});

/**
 * directionManager manager
 */
$di->setShared('directionManager', function () {
    return new \App\Models\Managers\DirectionManager();
});

/**
 * metroManager manager
 */
$di->setShared('metroManager', function () {
    return new \App\Models\Managers\MetroManager();
});

/**
 * metroLineManager manager
 */
$di->setShared('metroLineManager', function () {
    return new \App\Models\Managers\MetroLineManager();
});


