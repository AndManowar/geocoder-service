<?php

namespace App\Components;

use Phalcon\Mvc\Controller;
use Topnlab\PhalconBase\v2\Components\ApiRequest;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * Контролер клиентского  API
 *
 * Class MainController
 * @package App\Controllers
 *
 * @property ApiResponse $response
 * @property ApiRequest $request
 */
class BaseController extends Controller
{
    /**
     *
     */
    public function initialize(): void
    {
        // View по умолчанию отключен
        $this->view->disable();

        //TODO проверка авторизации
    }

    /**
     * Стандартный ответ CORS (OPTIONS)
     * @param string $url
     * @return \Topnlab\PhalconBase\v2\Components\ApiResponse
     */
    public function corsAction($url = '')
    {
        return $this->response->sendOptions();
    }
}