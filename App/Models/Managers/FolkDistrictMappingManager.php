<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 06.06.18
 * Time: 13:16
 */

namespace App\Models\Managers;

use App\Exceptions\FolkDistrictNotFoundException;
use App\Exceptions\InvalidMappingDataException;
use App\Exceptions\ObjectNotFoundException;
use App\Models\DB\FolkDistrictMappingModel;
use App\Models\DB\ObjectModel;
use App\Validators\FolkDistrictMappingValidator;

/**
 * Class FolkDistrictManager
 * @package App\Models\Managers
 */
class FolkDistrictMappingManager extends AbstractModelManager
{
    /**
     * @var string
     */
    public static $modelClass = FolkDistrictMappingModel::class;

    /**
     *
     */
    public function initialize()
    {
        $this->validator = new FolkDistrictMappingValidator();
    }

    /**
     * Привязка народного района к формальному
     *
     * @param array $data
     * @return bool
     * @throws \App\Exceptions\ObjectNotFoundException
     * @throws FolkDistrictNotFoundException
     * @throws InvalidMappingDataException
     * @throws ObjectNotFoundException
     */
    public function createMapping(array $data): bool
    {
        if (!$this->validator->validateData($data)) {
            return false;
        }

        if (!$this->checkIsFolkDistrict($data['folk_district_glob_id'])) {
            throw new FolkDistrictNotFoundException("District with glob_id {$data['folk_district_glob_id']} Is Not Folk");
        }

        if (!$this->checkDistrictsParent($data['formal_district_glob_id'], $data['folk_district_glob_id'])) {
            throw new InvalidMappingDataException("Formal district and Folk district have different parents");
        }

        if ($this->isMappingExists($data['formal_district_glob_id'], $data['folk_district_glob_id'])) {
            throw new InvalidMappingDataException("Mapping already exists");
        }

        return $this->findOrCreateStrictException()->assign($data)->save();
    }

    /**
     * Обновление привязки
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws FolkDistrictNotFoundException
     * @throws InvalidMappingDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function updateMapping(int $id, array $data): bool
    {
        if (!$this->validator->validateData($data)) {
            return false;
        }

        if (!$this->checkIsFolkDistrict($data['folk_district_glob_id'])) {
            throw new FolkDistrictNotFoundException("District with glob_id {$data['folk_district_glob_id']} Is Not Folk");
        }

        if (!$this->checkDistrictsParent($data['formal_district_glob_id'], $data['folk_district_glob_id'])) {
            throw new InvalidMappingDataException("Formal district and Folk district have different parents");
        }

        if ($this->isMappingExists($data['formal_district_glob_id'], $data['folk_district_glob_id'])) {
            throw new InvalidMappingDataException("Mapping already exists");
        }

        return $this->findOrCreateStrictException($id)->assign($data)->save();
    }

    /**
     * "Мягкое" удаление привязки
     *
     * @param int $id
     * @return bool
     * @throws \App\Exceptions\ObjectNotFoundException
     */
    public function deleteMapping(int $id): bool
    {
        return $this->findOrCreateStrictException($id)->delete();
    }

    /**
     * Проверка, является ли район народным
     *
     * @param string $folkDistrictId
     * @return bool
     * @throws ObjectNotFoundException
     */
    protected function checkIsFolkDistrict(string $folkDistrictId): bool
    {
        $folkDistrict = $this->findFolkDistrict($folkDistrictId);

        return $folkDistrict->info->is_folk_district;
    }

    /**
     * Проверка, являются ли оба района потомками одного и того же предка
     *
     * @param string $formalDistrictGlobId
     * @param string $folkDistrictGlobId
     * @return bool
     * @throws ObjectNotFoundException
     */
    protected function checkDistrictsParent(string $formalDistrictGlobId, string $folkDistrictGlobId): bool
    {
        /** @var ObjectModel $formalDistrict */
        $formalDistrict = ObjectModel::query()
            ->where('glob_id = :formalDistrictGlobId: AND status = :activeStatus:', [
                'formalDistrictGlobId' => $formalDistrictGlobId,
                'activeStatus'         => ObjectModel::STATUS_ACTIVE
            ])
            ->execute()
            ->getFirst();

        return $this->findFolkDistrict($folkDistrictGlobId)->parent_glob_id === $formalDistrict->parent_glob_id;
    }

    /**
     * Найти народный район с текущим folk_district_glob_id
     *
     * @param string $folkDistrictId
     * @throws ObjectNotFoundException
     * @return ObjectModel|\Phalcon\Mvc\ModelInterface
     */
    protected function findFolkDistrict(string $folkDistrictId)
    {
        $folkDistrict = ObjectModel::query()
            ->where('glob_id = :folkDistrictId:', ['folkDistrictId' => $folkDistrictId])
            ->execute()
            ->getFirst();

        if (!$folkDistrict) {
            throw new ObjectNotFoundException("Object with glob_id '{$folkDistrictId}' Not Found");
        }

        return $folkDistrict;
    }

    /**
     * Проверка на существования такой привязки
     *
     * @param string $formalDistrictGlobId
     * @param string $folkDistrictGlobId
     * @return bool
     */
    protected function isMappingExists(string $formalDistrictGlobId, string $folkDistrictGlobId): bool
    {
        return $this->findFirstByAttributes([
                'formal_district_glob_id' => $formalDistrictGlobId,
                'folk_district_glob_id'   => $folkDistrictGlobId
            ]) !== null;
    }
}