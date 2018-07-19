<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 17:15
 */

namespace App\Controllers\Service;

use App\Components\BaseController;
use App\Models\Managers\MetroLineManager;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * CRUD линий метро
 *
 * Class MetroLineController
 * @package App\Controllers\Admin
 *
 * @property MetroLineManager $metroLineManager
 */
class MetroLineController extends BaseController
{
    /**
     * Получение линий метро для селекта
     *
     * @return ApiResponse
     */
    public function getLinesAction(): ApiResponse
    {
        return $this->response->sendSuccess($this->metroLineManager->getAll());
    }

    /**
     * Получение линии метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function getLineAction(int $id): ApiResponse
    {
        return $this->response->sendSuccess($this->metroLineManager->get($id));
    }

    /**
     * Создание линии метро
     *
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function createLineAction(): ApiResponse
    {
        if ($this->metroLineManager->createObject($this->request->getRequestDataParam()())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->metroLineManager->getValidationErrors());
    }

    /**
     * Обновление линии метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function updateLineAction(int $id): ApiResponse
    {
        if ($this->metroLineManager->updateObject($id, $this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->metroLineManager->getValidationErrors());
    }

    /**
     * Мягкое удаление линии метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteLineAction(int $id): ApiResponse
    {
        if ($this->metroLineManager->delete($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Восстановление линии метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function recoverLineAction(int $id): ApiResponse
    {
        if ($this->metroLineManager->recover($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }
}