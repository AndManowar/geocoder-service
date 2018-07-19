<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 24.05.18
 * Time: 13:05
 */

namespace App\Models\Managers;

use App\Exceptions\TypeNotFoundException;
use App\Models\DB\ObjectInfoModel;
use App\Models\DB\ObjectModel;
use App\Models\DB\ObjectTypeModel;
use App\Service\SearchService;
use App\Validators\ObjectValidator;
use App\Exceptions\ObjectNotFoundException;
use App\Interfaces\CrudObjectManagerInterface;
use App\Exceptions\InvalidLevelValueException;
use App\Exceptions\InvalidParentDataException;
use App\Exceptions\ParentDataNotFoundException;
use InvalidArgumentException;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Row;

/**
 * Class ObjectManager
 * @package App\Models\Managers
 */
class ObjectManager extends AbstractModelManager implements CrudObjectManagerInterface
{

    /**
     * Типы сущностей в админке
     *
     * @const
     */
    const TYPE_COUNTY = 1;         //Округа
    const TYPE_DISTRICT = 2;       // Районы
    const TYPE_CITY = 3;           // Населенные пункты
    const TYPE_CITY_DISTRICT = 4;  // Районы нас. пунктов
    const TYPE_STREET = 5;         // Улицы

    /**
     * Типы объектов для маппинга сущностей из админки к типам объектов
     *
     * @const
     */
    const SUBTYPE_COUNTY = 273;
    const SUBTYPE_DISTRICT = 11;
    const SUBTYPE_CITY_DISTRICT = 28;

    /**
     * Маппинг сущностей для ограничения селектов на фронте.
     * Напр. если добавляют округ, то можно выбрать только регион в селекте,
     * и дальнейшие "потомки" возвращатся не будут
     *
     * @const
     */
    const MAPPING_TYPE_MAX_LEVEL = [
        self::TYPE_COUNTY        => ObjectModel::LEVEL_REGION,
        self::TYPE_DISTRICT      => ObjectModel::LEVEL_AREA,
        self::TYPE_CITY_DISTRICT => ObjectModel::LEVEL_CITY,
        self::TYPE_STREET        => ObjectModel::LEVEL_ADDITIONAL_TERRITORY,
    ];

    /**
     * Маппинг сущностей к object_types
     * Для улицы - приходит с фронта с селекта
     *
     * @const
     */
    const MAPPING_OBJECT_TYPE_TO_SUBTYPE = [
        self::TYPE_COUNTY   => self::SUBTYPE_COUNTY,
        self::TYPE_DISTRICT => self::SUBTYPE_DISTRICT,
    ];

    /**
     * Key for parent data in request
     *
     * @const
     */
    const REQUEST_PARENT_DATA_KEY = 'parentData';

    /**
     * End date const value
     *
     * @const
     */
    const END_DATE_VALUE = '2200-01-01';

    /**
     * @var
     */
    public $folkDistrictGlobId;

    /**
     * Parent data request array
     *
     * @var array
     */
    private $parentData;

    /**
     * Last parent object
     *
     * @var ObjectModel
     */
    private $lastParent;

    /**
     * Определяем, что добавляемая сущность есть народным районом
     *
     * @var bool
     */
    private $isFolk = false;

    /**
     * Attributes from last parent to load into current object on create
     *
     * @var array
     */
    private $whiteList = [
        'region_id',
        'area_id',
        'autonomy_id',
        'city_id',
        'city_district_id',
        'place_id',
        'street_id'
    ];

    /**
     * @var string
     */
    public static $modelClass = ObjectModel::class;

    /**
     *
     */
    public function initialize()
    {
        $this->validator = new ObjectValidator();
    }

    /**
     * Get record by id
     *
     * @param int $id
     * @return Model
     * @throws ObjectNotFoundException
     */
    public function get(int $id): Model
    {
        $object = $this->findFirstByAttributes(['id' => $id]);
        if (!$object) {
            throw new ObjectNotFoundException("Object with id {$id} not found!");
        }

        return $object;
    }

    /**
     * Create object method
     *
     * @param array $data
     * @return boolean
     * @throws ObjectNotFoundException
     * @throws ParentDataNotFoundException
     * @throws InvalidParentDataException
     * @throws InvalidLevelValueException
     * @throws TypeNotFoundException
     * @throws \Exception
     */
    public function createObject(array $data): bool
    {
        if (!$data['type']) {
            throw new InvalidArgumentException('Type must be set');
        }

        if (!$this->validator->validateData($data)) {
            return false;
        }

        /** @var ObjectModel $object */
        $object = $this->findOrCreateStrictException();
        $object->info = new ObjectInfoModel();
        $this->buildObjectInfo($object, $data);
        $this->buildObject($object, $data);
        $this->incrementObjectField($object);

        if (!$this->validator->validateObjectAfterBuilding($object)) {
            return false;
        }

        if (!$object->save()) {
            $this->validator->setDbErrors($object->getMessages());
            return false;
        }

        return true;
    }

    /**
     * Update object method
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws ObjectNotFoundException
     * @throws ParentDataNotFoundException
     * @throws InvalidParentDataException
     * @throws InvalidLevelValueException
     * @throws TypeNotFoundException
     * @throws \Exception
     */
    public function updateObject(int $id, array $data): bool
    {
        if (!$data['type']) {
            throw new InvalidArgumentException('Type must be set');
        }

        if (!$this->validator->validateData($data)) {
            return false;
        }

        /** @var ObjectModel $object */
        $object = $this->findOrCreateStrictException($id);
        $this->buildObjectInfo($object, $data);
        $this->buildObject($object, $data);

        if (!$object->save()) {
            $this->validator->setDbErrors($object->getMessages());
            return false;
        }

        return true;
    }

    /**
     * Object Soft delete
     *
     * @param int $id
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function delete(int $id): bool
    {
        return $this->findOrCreateStrictException($id)->delete();
    }

    /**
     * Recover deleted object
     *
     * @param int $id
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function recover(int $id): bool
    {
        return $this->findOrCreateStrictException($id)->save(['status' => ObjectModel::STATUS_ACTIVE]);
    }

    /**
     * Меняем свойство идентификации народного района
     *
     * @return ObjectManager
     */
    public function setFolkDistrict(): self
    {
        $this->isFolk = true;

        return $this;
    }

    /**
     * Формирование объекта, заполнение полями из предка, построение дерева ролителей.
     *
     * @param ObjectModel $object
     * @param array $data
     * @throws InvalidLevelValueException
     * @throws TypeNotFoundException
     * @throws \Exception
     */
    private function buildObject(ObjectModel $object, array $data): void
    {
        $object->assign($data);

        if (!$object->type_id) {
            if (!isset(self::MAPPING_OBJECT_TYPE_TO_SUBTYPE[$data['type']])) {
                throw new TypeNotFoundException('Object type must be set');
            }

            $object->type_id = self::MAPPING_OBJECT_TYPE_TO_SUBTYPE[$data['type']];
        }

        $object->level = $this->getLevel($object, $data['type']);
        $object->glob_id = $this->generateHash();
        $object->full_name = $object->name;
        $object->parent_glob_id = $this->lastParent->glob_id;
        $object->status = ObjectModel::STATUS_ACTIVE;

        if ($this->isFolk) {
            $this->folkDistrictGlobId = $object->glob_id;
        }
    }

    /**
     * Build object info
     *
     * @param ObjectModel $object
     * @param array $data
     * @return void
     * @throws InvalidLevelValueException
     * @throws InvalidParentDataException
     * @throws ObjectNotFoundException
     * @throws ParentDataNotFoundException
     * @throws \Exception
     */
    private function buildObjectInfo(ObjectModel $object, array $data): void
    {
        $this->setParentDataFromRequest($data);
        $this->checkParentData();

        if (!$object->info->end_date) {
            $object->info->end_date = self::END_DATE_VALUE;
        }

        $object->info->assign($data);
        $object->info->is_folk_district = $this->isFolk;
        $object->info->fias_entry_id = $this->generateHash();

        while (!$this->validator->validateUniqueFias($object->info->fias_entry_id)) {
            $object->info->fias_entry_id = $this->generateHash();
        }

        $object->info->parent_data = $this->buildParentTree();
    }

    /**
     * Set parent data
     *
     * @param array $request
     * @return void
     * @throws ParentDataNotFoundException
     */
    private function setParentDataFromRequest(array $request): void
    {
        if (!isset($request[self::REQUEST_PARENT_DATA_KEY])) {
            throw new ParentDataNotFoundException('Parent Data Not Found');
        }

        $this->parentData = $request[self::REQUEST_PARENT_DATA_KEY];
    }

    /**
     * Check parent data
     *
     * @return void
     * @throws InvalidParentDataException
     * @throws ObjectNotFoundException
     * @throws InvalidLevelValueException
     */
    private function checkParentData(): void
    {
        $searchService = new SearchService();

        foreach ($this->parentData as $id => $parentDataItem) {
            /*
             * Проверка валидности присланного дерева парентов с фронта
             * Если пришел регион и город, проверяем только в регионе наличие города
             * Если пришел регион, город, поселок, то проверяем только регион на наличие города и город на поселок
             * в поселок не лезем, для того и нужен $id + 2
             */
            if (isset($this->parentData[$id + 2])) {
                $itemChildren = $searchService->getChildren($parentDataItem['glob_id']);
                if (!in_array($this->parentData[$id + 1]['glob_id'], array_column($itemChildren['items'], 'glob_id'))) {
                    throw new InvalidParentDataException("Parent Data Tree Is Invalid");
                }
            }
        }
    }

    /**
     * Build parent data tree
     *
     * @return string
     */
    private function buildParentTree(): string
    {
        $result = [];

        foreach ($this->parentData as $parentItem) {

            // Если регион (у него глоб_ид будет интовым)
            if (is_numeric($parentItem['glob_id'])) {
                $result[] = ObjectModel::query()
                    ->where('region_id = :glob_id: AND level = :region_level: AND status = :active_status:', [
                        'glob_id'       => $parentItem['glob_id'],
                        'region_level'  => ObjectModel::LEVEL_REGION,
                        'active_status' => ObjectModel::STATUS_ACTIVE
                    ])
                    ->execute()
                    ->getFirst();
            } else {
                $result[] = ObjectModel::query()
                    ->where("glob_id = :glob_id: AND status = :active_status:", [
                        'glob_id'       => $parentItem['glob_id'],
                        'active_status' => ObjectModel::STATUS_ACTIVE
                    ])
                    ->execute()
                    ->getFirst();
            }
        }

        // Получаем ласт парента, нужен для записей полей из него в чайлда
        $this->lastParent = end($result);

        return json_encode($result);
    }

    /**
     * Get child level by last parent level
     *
     * @param ObjectModel $object
     * @param int $type
     * @return int
     * @throws InvalidLevelValueException
     * @throws TypeNotFoundException
     */
    private function getLevel(ObjectModel $object, int $type): int
    {
        if (!array_key_exists($this->lastParent->level, SearchService::$levelsSteps)) {
            throw new InvalidLevelValueException(" Level {$this->lastParent->level} Is Invalid");
        }

        $query = ObjectTypeModel::query();

        if ($type == self::TYPE_STREET) {
            $query->where('id = :type_id: AND level > :last_parent_level: AND status = :active_status:', [
                'type_id'           => $object->type_id,
                'last_parent_level' => $this->lastParent->level,
                'active_status'     => ObjectTypeModel::STATUS_ACTIVE
            ]);
        } else {
            $query->where('id = :type_id: AND status = :active_status:', [
                'type_id'       => $object->type_id,
                'active_status' => ObjectTypeModel::STATUS_ACTIVE
            ]);
        }

        /** @var ObjectTypeModel $currentType */
        $currentType = $query->execute()->getFirst();

        if (!$currentType) {
            throw new TypeNotFoundException("Type With ID {$object->type_id} Not Found");
        }

        return $currentType->level;
    }

    /**
     * Incrementation of object field
     *
     * @param ObjectModel $object
     * @return void
     * @throws InvalidLevelValueException
     */
    private function incrementObjectField(ObjectModel $object): void
    {
        switch ($object->level) {
            case ObjectModel::LEVEL_AREA:
            case ObjectModel::LEVEL_COUNTY:
                $column = 'area_id';
                break;
            case ObjectModel::LEVEL_CITY:
                $column = 'city_id';
                break;
            case ObjectModel::LEVEL_CITY_DISTRICT:
                $column = 'city_district_id';
                break;
            case ObjectModel::LEVEL_PLACE:
                $column = 'place_id';
                break;
            case ObjectModel::LEVEL_STREET:
                $column = 'street_id';
                break;
            case ObjectModel::LEVEL_ADDITIONAL_TERRITORY:
            case ObjectModel::LEVEL_ADDITIONAL_TERRITORY_SUBJECT:
                $column = 'external_id';
                break;
            default:
                throw new InvalidLevelValueException("Level {$object->level} Is Invalid");
                break;
        }

        /** @var Row $maxFieldValue */
        $maxFieldValue = ObjectModel::query()
            ->columns("MAX({$column})")
            ->where('parent_glob_id = :parent_glob_id:', ['parent_glob_id' => $object->parent_glob_id])
            ->execute()
            ->getFirst();

        // Т.к. улицы, города и т.д нумеруются по возростанию в рамках контейнера - инкрементируем максимальное значение поля в рамках контейнера
        $object->assign($this->lastParent->toArray(), null, $this->whiteList);

        $object->$column = !isset($maxFieldValue->toArray()[0]) ? 1 : $maxFieldValue->toArray()[0] + 1;
    }

    /**
     * Generate glob id
     *
     * @return string
     * @throws \Exception
     */
    private function generateHash(): string
    {
        return md5(random_bytes(46));
    }
}