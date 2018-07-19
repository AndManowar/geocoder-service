<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 23.05.18
 * Time: 8:55
 */

namespace App\Controllers\Service;

use App\Components\BaseController;
use App\Models\Managers\ObjectManager;
use App\Models\Managers\ObjectTypeManager;
use App\Service\Filters\ObjectFilter;
use App\Service\SearchService;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * Class CrudController
 * @package App\Controllers
 *
 * @property ObjectManager $objectManager
 * @property ObjectTypeManager $typeManager
 */
class CrudController extends BaseController
{
    /**
     * Пагинация и фильтрация
     *
     * @return ApiResponse
     * @throws \App\Exceptions\InvalidFilterClassException
     */
    public function indexAction(): ApiResponse
    {
        return $this->response->sendSuccess((new ObjectFilter())->getPaginatedAndSortedData($this->request->get()));
    }

    /**
     * Get object action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function getObjectAction(int $id): ApiResponse
    {
        return $this->response->sendSuccess($this->objectManager->get($id));
    }

    /**
     * Create object function
     *
     * @return ApiResponse
     * @throws \App\Exceptions\InvalidLevelValueException
     * @throws \App\Exceptions\InvalidParentDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     * @throws \App\Exceptions\ParentDataNotFoundException
     * @throws \App\Exceptions\TypeNotFoundException
     * @throws \Exception
     */
    public function createObjectAction(): ApiResponse
    {
        if ($this->objectManager->createObject($this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->objectManager->getValidationErrors());
    }

    /**
     * Update object function
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\InvalidLevelValueException
     * @throws \App\Exceptions\InvalidParentDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     * @throws \App\Exceptions\ParentDataNotFoundException
     * @throws \App\Exceptions\TypeNotFoundException
     * @throws \Exception
     */
    public function updateObjectAction(int $id): ApiResponse
    {
        if ($this->objectManager->updateObject($id, $this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->objectManager->getValidationErrors());
    }

    /**
     * Delete action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteObjectAction(int $id): ApiResponse
    {
        if ($this->objectManager->delete($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Recover deleted record action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function recoverObjectAction(int $id): ApiResponse
    {
        if ($this->objectManager->recover($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Get model action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function getTypeAction(int $id)
    {
        return $this->response->sendSuccess($this->typeManager->get($id));
    }

    /**
     * Create type action
     *
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function createTypeAction(): ApiResponse
    {
        if ($this->typeManager->createObject($this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->typeManager->getValidationErrors());
    }

    /**
     * Update type action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function updateTypeAction(int $id): ApiResponse
    {
        if ($this->typeManager->updateObject($id, $this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->typeManager->getValidationErrors());
    }

    /**
     * Delete type action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteTypeAction(int $id): ApiResponse
    {
        if ($this->typeManager->delete($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Recover deleted record action
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function recoverTypeAction(int $id): ApiResponse
    {
        if ($this->typeManager->recover($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }

    /**
     * Получение детей для селектов админки
     *
     * @param string $globId
     * @param int $type
     * @return ApiResponse
     * @throws \App\Exceptions\InvalidLevelValueException
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function getChildrenAction(string $globId, int $type): ApiResponse
    {
        return $this->response->sendSuccess((new SearchService($this->modelsManager))->getChildren($globId, $type, true));
    }

    /**
     * Получение списка регионов для селекта
     *
     * @return ApiResponse
     */
    public function getRegionsAction(): ApiResponse
    {
        return $this->response->sendSuccess((new SearchService($this->modelsManager))->getRegionsList());
    }

    /**
     * Получение typeDesc по Type
     *
     * @param int $type
     * @return ApiResponse
     */
    public function getTypeDescsAction(int $type): ApiResponse
    {
        return $this->response->sendSuccess((new SearchService($this->modelsManager))->getTypeDescs($type));
    }
}