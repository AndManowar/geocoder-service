<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 12:41
 */

namespace App\Controllers\Service;

use App\Components\BaseController;
use App\Models\Managers\MetroManager;
use App\Service\Filters\MetroFilter;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * Контроллер для работы с метро
 *
 * Class MetroController
 * @package App\Controllers\Admin
 *
 * @property MetroManager $metroManager
 */
class MetroController extends BaseController
{
    /**
     * Пагинированный список метро
     *
     * @return ApiResponse
     * @throws \App\Exceptions\InvalidFilterClassException
     */
    public function indexAction(): ApiResponse
    {
        return $this->response->sendSuccess((new MetroFilter())->getPaginatedAndSortedData($this->request->get()));
    }

    /**
     * Получение записи метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function getMetroAction(int $id): ApiResponse
    {
        return $this->response->sendSuccess($this->metroManager->get($id));
    }

    /**
     * Создание метро
     *
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function createMetroAction(): ApiResponse
    {
        if ($this->metroManager->createObject($this->request->getRequestDataParam()())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->metroManager->getValidationErrors());
    }

    /**
     * Обновление метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function updateMetroAction(int $id): ApiResponse
    {
        if ($this->metroManager->updateObject($id, $this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->metroManager->getValidationErrors());
    }

    /**
     * Мягкое удаление метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteMetroAction(int $id): ApiResponse
    {
        if ($this->metroManager->delete($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Восстановление удаленного метро
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function recoverMetroAction(int $id): ApiResponse
    {
        if ($this->metroManager->recover($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }
}