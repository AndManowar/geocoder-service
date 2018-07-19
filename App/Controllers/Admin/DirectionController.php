<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 11:59
 */

namespace App\Controllers\Admin;

use App\Components\BaseController;
use App\Models\Managers\DirectionManager;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * Class DirectionController
 * @package App\Controllers\Admin
 *
 * @property DirectionManager $directionManager
 */
class DirectionController extends BaseController
{
    /**
     * Получение направления по ID
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function getDirectionAction(int $id): ApiResponse
    {
        return $this->response->sendSuccess($this->directionManager->get($id));
    }

    /**
     * Создание нового направления
     *
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function createDirectionAction(): ApiResponse
    {
        if ($this->directionManager->createObject($this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->directionManager->getValidationErrors());
    }

    /**
     * Обновление направления
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function updateDirectionAction(int $id): ApiResponse
    {
        if ($this->directionManager->updateObject($id, $this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->directionManager->getValidationErrors());
    }

    /**
     * Мягкое удаление направления
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteDirectionAction(int $id): ApiResponse
    {
        if ($this->directionManager->delete($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Восстановление удаленного направления
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function recoverDirectionAction(int $id): ApiResponse
    {
        if ($this->directionManager->recover($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }
}