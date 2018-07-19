<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 06.06.18
 * Time: 12:36
 */

namespace App\Controllers\Admin;

use App\Components\BaseController;
use App\Models\Managers\FolkDistrictMappingManager;
use App\Models\Managers\ObjectManager;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * Контроллер для работы с народными районами
 *
 * Class FolkDistrictController
 * @package App\Controllers
 *
 * @property ObjectManager $objectManager
 * @property FolkDistrictMappingManager folkDistrictMappingManager
 */
class DistrictController extends BaseController
{
    /**
     * Create folk district action
     *
     * @return ApiResponse
     * @throws \App\Exceptions\InvalidLevelValueException
     * @throws \App\Exceptions\InvalidParentDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     * @throws \App\Exceptions\ParentDataNotFoundException
     * @throws \App\Exceptions\TypeNotFoundException
     * @throws \Exception
     */
    public function createFolkDistrictAction(): ApiResponse
    {
        if ($this->objectManager->setFolkDistrict()->createObject($this->request->getRequestDataParam()())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->objectManager->getValidationErrors());
    }

    /**
     * Update folk district action
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
    public function updateFolkDistrictAction(int $id): ApiResponse
    {
        if ($this->objectManager->setFolkDistrict()->updateObject($id, $this->request->getRequestDataParam())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->objectManager->getValidationErrors());
    }

    /**
     * Привязать народный район к формальному
     *
     * @return ApiResponse
     * @throws \App\Exceptions\FolkDistrictNotFoundException
     * @throws \App\Exceptions\InvalidMappingDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function createFolkDistrictMappingAction(): ApiResponse
    {
        if ($this->folkDistrictMappingManager->createMapping($this->request->getRequestDataParam()())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->folkDistrictMappingManager->getValidationErrors());
    }

    /**
     * Обновление привязки народного района к формальному
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\FolkDistrictNotFoundException
     * @throws \App\Exceptions\InvalidMappingDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function updateFolkDistrictMappingAction(int $id): ApiResponse
    {
        if ($this->folkDistrictMappingManager->updateMapping($id, $this->request->getRequestDataParam()())) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError($this->folkDistrictMappingManager->getValidationErrors());
    }

    /**
     * Удаление привязки народного района к формальному
     *
     * @param int $id
     * @return ApiResponse
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteFolkDistrictMappingAction(int $id): ApiResponse
    {
        if ($this->folkDistrictMappingManager->deleteMapping($id)) {
            return $this->response->sendSuccess();
        }

        return $this->response->sendError();
    }
}