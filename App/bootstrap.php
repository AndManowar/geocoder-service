<?php
/**
 * Phalcon rest api bootstrap file.
 * Synchronous response
 *
 * Top & Lab v 2.0
 * Phalcon Small Service Application Template
 *
 * @author lex gudx  sd1328@gmail.com
 */
define('BASE_PATH', __DIR__ . '/../');
define('APP_PATH', BASE_PATH . '/App');
try {
    include_once BASE_PATH . '/vendor/autoload.php';
    // Using the App factory default services container
    $di = new \Phalcon\Di\FactoryDefault();
    // Add Config Service
    $di->setShared('config', function () {
        // Load Global Settings
        $confCollection = include APP_PATH . '/config/config.php';
        $config = new \Phalcon\Config($confCollection);
        if (defined('APP_ENV')) {
            // Load environment settings
            $envConfCollection = include APP_PATH . '/config/config.' . APP_ENV . '.php';
            $envConfig = new \Phalcon\Config($envConfCollection);
            $config->merge($envConfig);
        }
        return $config;
    });
    // Read Service to app container
    include APP_PATH . '/config/service.php';
    // Register Namespace Autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerNamespaces([
        'App' => APP_PATH . '/',
    ])->register();
    /**
     * Заглушка
     */
    $di->setShared('view', function () {
        // view не используется но требуется для  приложения \Phalcon\Mvc\Application
        return new \Phalcon\Mvc\View();
    });
    /**
     * Компонент Роутинга
     */
    $di->setShared('router', function () {
        $router = new \Phalcon\Mvc\Router(false);
        // Загрузка масива обявленных роутов
        $routList = include APP_PATH . '/router.php';
        // Объявление роутов
        foreach ($routList as $rout) {
            $router->add($rout[1], [
                'namespace'  => $rout[2][0],
                'controller' => $rout[2][1],
                'action'     => $rout[2][2],
            ], $rout[0]);
        }

        return $router;
    });
    // Create a REST application
    $app = new \Phalcon\Mvc\Application();
    $app->setDI($di);
    // Обработка исключений
    try {
        echo $app->handle()->getContent();
    } catch (\Exception $e) {
        // Исключение связаное с отказом в доступе
        if ($e instanceof \Topnlab\Common\v2\Api\Exception\ApiNotAuthorizedServiceException) {
            $app->response->sendForbidden()->send();
        } else {  // другое
            $app->response->sendError($e->getMessage())->send();
        }
    }
} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
        'error' => $e->getMessage(),
    ]);
}
