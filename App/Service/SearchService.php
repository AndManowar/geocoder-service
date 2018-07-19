<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 23.05.18
 * Time: 12:17
 */

namespace App\Service;

use App\Exceptions\InvalidLevelValueException;
use App\Exceptions\ObjectNotFoundException;
use App\Models\DB\ObjectInfoModel;
use App\Models\DB\ObjectModel;
use App\Models\DB\ObjectTypeModel;
use App\Models\Managers\ObjectManager;
use PDO;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Query\Builder;

/**
 * Class SearchService
 *
 * @package App\Services
 */
class SearchService
{
    /**
     * Max possible lvl
     *
     * @const
     */
    const MAX_POSSIBLE_LVL = 91;

    /**
     * Min level for child searching at first iteration
     *
     * @var int
     */
    private $minLevel;

    /**
     * Max level for child searching at first iteration
     *
     * @var int
     */
    private $maxLevel;

    /**
     * DB field to search parents
     *
     * @var string
     */
    private $searchField = 'parent_glob_id';

    /**
     * Список полей для стандартного select-та
     * Для примера: 'key' => 'value', в строителе запросов будет как `value as key`
     *
     * @var array
     */
    private $queryColumns = [
        'glob_id'   => 'glob_id',
        'name'      => 'name',
        'level'     => 'level',
        'region_id' => 'region_id',
        'type_id'   => 'type_id'
    ];

    /**
     * Менеджер моделей
     *
     * @var Manager
     */
    private $modelsManager;

    /**
     * @var Builder
     */
    private $searchQuery = null;

    /**
     * Порядок переходов уровней (указывает очередность уровней вложенности)
     *
     * @var array
     */
    public static $levelsSteps = [
        ObjectModel::LEVEL_REGION               => ObjectModel::LEVEL_COUNTY,
        ObjectModel::LEVEL_COUNTY               => ObjectModel::LEVEL_AREA,
        ObjectModel::LEVEL_AREA                 => ObjectModel::LEVEL_CITY,
        ObjectModel::LEVEL_CITY                 => ObjectModel::LEVEL_CITY_DISTRICT,
        ObjectModel::LEVEL_CITY_DISTRICT        => ObjectModel::LEVEL_PLACE,
        ObjectModel::LEVEL_PLACE                => ObjectModel::LEVEL_STREET,
        ObjectModel::LEVEL_STREET               => ObjectModel::LEVEL_ADDITIONAL_TERRITORY,
        ObjectModel::LEVEL_ADDITIONAL_TERRITORY => ObjectModel::LEVEL_ADDITIONAL_TERRITORY_SUBJECT
    ];

    /**
     * Маппинг тайпов к typeDesc-ам
     *
     * @var array
     */
    private $mappingTypeToLevels = [
        ObjectManager::TYPE_CITY          => [
            ObjectModel::LEVEL_CITY
        ],
        ObjectManager::TYPE_CITY_DISTRICT => [
            ObjectModel::LEVEL_CITY_DISTRICT,
            ObjectModel::LEVEL_PLACE
        ],
        ObjectManager::TYPE_STREET        => [
            ObjectModel::LEVEL_STREET,
            ObjectModel::LEVEL_ADDITIONAL_TERRITORY,
            ObjectModel::LEVEL_ADDITIONAL_TERRITORY_SUBJECT
        ]
    ];

    /**
     * Конструктор
     *
     * @param Manager $modelManager
     */
    public function __construct(Manager $modelManager = null)
    {
        $this->modelsManager = $modelManager;
    }

    /**
     * Построитель поисковых запросов
     *
     * @return SearchService
     */
    private function searchQueryBuilder()
    {
        $this->searchQuery = null;
        $this->searchField = 'object.name';
        $this->queryColumns = array_merge($this->queryColumns, [
            'name'        => 'object.name as object_name',
            'level'       => 'object.level',
            'type_name'   => 'type.name as type_name',
            'parent_data' => 'info.parent_data',
        ]);

        $this->searchQuery = $this->modelsManager->createBuilder()
            ->from(['object' => ObjectModel::class])
            ->columns(array_values($this->queryColumns))
            ->leftJoin(ObjectTypeModel::class, 'type.id = type_id', 'type')
            ->leftJoin(ObjectInfoModel::class, 'info.object_id = object.id', 'info')
            ->where("object.status = :status:", ['status' => ObjectModel::STATUS_ACTIVE], PDO::PARAM_INT);

        return $this;
    }

    /**
     * Поиск по имени заданому имени
     *
     * @param string $name
     * @return SearchService
     */
    private function whereName(string $name): SearchService
    {
        $this->searchQuery->andWhere("{$this->searchField} like :name:", ['name' => '%' . $name . '%'], PDO::PARAM_STR);

        return $this;
    }

    /**
     * Учитывать допустимые уровни в поиске
     *
     * @param array $levels
     * @return SearchService
     */
    private function whereLevel(array $levels): SearchService
    {
        $this->searchQuery->inWhere("object.level", $levels, Builder::OPERATOR_AND);

        return $this;
    }

    /**
     * Учитывать регион в поиске
     *
     * @param int $regionId
     * @return SearchService
     */
    private function whereRegion(int $regionId): SearchService
    {
        $this->searchQuery->andWhere("region_id = :regionId:", ['regionId' => $regionId], PDO::PARAM_INT);

        return $this;
    }

    /**
     * Учитывать район в поиске
     *
     * @param int $areaId
     * @return $this
     */
    private function whereArea(int $areaId = null): SearchService
    {
        if (!is_null($areaId)) {
            $this->searchQuery->andWhere("area_id = :areaId:", ['areaId' => $areaId], PDO::PARAM_INT);
        }

        return $this;
    }

    /**
     * Учитывать город в поиске
     *
     * @param int $cityId
     * @param int $placeId
     * @return $this
     */
    private function whereCityOrPlace(int $cityId = null, int $placeId = null): SearchService
    {
        if (!is_null($cityId)) {
            $this->searchQuery->andWhere("city_id = :cityId::", ['cityId' => $cityId], PDO::PARAM_INT);
        }

        if (!is_null($placeId)) {
            $this->searchQuery->andWhere("place_id = :placeId:", ['placeId' => $placeId], PDO::PARAM_INT);
        }

        return $this;
    }

    /**
     * Результат поиска
     *
     * @return array
     */
    private function getSearchResult()
    {
        return $this->searchQuery
            ->orderBy('region_id ASC')
            ->getQuery()
            ->execute()
            ->toArray();
    }

    /**
     * Поиск региона по имени
     *
     * @param string $regionName
     * @return array
     */
    public function searchRegion(string $regionName): array
    {
        return $this->searchQueryBuilder()
            ->whereName($regionName)
            ->whereLevel([ObjectModel::LEVEL_REGION])
            ->getSearchResult();
    }

    /**
     * Поиск района по имени внутри региона
     *
     * @param string $name
     * @param int $regionId
     * @return array
     */
    public function searchArea(string $name, int $regionId): array
    {
        return $this->searchQueryBuilder()
            ->whereName($name)
            ->whereRegion($regionId)
            ->whereLevel([ObjectModel::LEVEL_AREA])
            ->getSearchResult();
    }

    /**
     * Поиск города по имени внутри региона + района
     *
     * @param string $name
     * @param int $regionId
     * @param int $areaId
     * @return array
     */
    public function searchCity(string $name, int $regionId, int $areaId = null): array
    {
        return $this->searchQueryBuilder()
            ->whereName($name)
            ->whereRegion($regionId)
            ->whereArea($areaId)
            ->whereLevel([ObjectModel::LEVEL_CITY, ObjectModel::LEVEL_PLACE])
            ->getSearchResult();
    }

    /**
     * Поиск адреса по имени внутри региона + района + города
     *
     * @param string $name
     * @param int $regionId
     * @param int $areaId
     * @param int $cityId
     * @param int $placeId
     * @return array
     */
    public function searchStreet(string $name, int $regionId, int $areaId = null, int $cityId = null, int $placeId = null): array
    {
        return $this->searchQueryBuilder()
            ->whereName($name)
            ->whereRegion($regionId)
            ->whereArea($areaId)
            ->whereCityOrPlace($cityId, $placeId)
            ->whereLevel([ObjectModel::LEVEL_STREET, ObjectModel::LEVEL_ADDITIONAL_TERRITORY_SUBJECT])
            ->getSearchResult();
    }

    /**
     * Get children data for dropdown
     *
     * @param string $globId
     * @param int|null $type
     * @param bool $isForWidget
     * @return array
     * @throws InvalidLevelValueException
     * @throws ObjectNotFoundException
     */
    public function getChildren(string $globId, int $type = null, bool $isForWidget = false): array
    {
        $result = [];

        $level = $this->getLevelByGlobId($globId);

        // проверяем или достугнут  минимальный допустимый LEVEL для  TYPE (улица, район .....)
        // TYPE - константы ObjectManager
        if ($type != null && ObjectManager::MAPPING_TYPE_MAX_LEVEL[$type] <= $level) {
            return [];
        }

        $currentObjectLevel = $level;

        // Если в диапазоне уровней, который был установлен $this->getLevelsRange нету чайлдов - берем следующий по иерархии
        while (empty($result) && $level < self::MAX_POSSIBLE_LVL) {
            $result['items'] = $this->getQueryData($globId, $level);
            $level = self::$levelsSteps[$level];
        }

        if ($isForWidget) {
            //TODO пока оказалось не нужно. Если и в дальнейшем не будет нужно - удалить
            //$result['types'] = $this->getAvailableTypesByLevelsRange();
            $result['title'] = $this->getFieldTitleByLevel($currentObjectLevel);
        }

        return $result;
    }

    /**
     * Список регионов для селекта
     *
     * @return array
     */
    public function getRegionsList()
    {
        $result = [];

        $result['items'] = ObjectModel::query()
            ->columns(['region_id', 'name'])
            ->where("level = :level: and status = :active:", [
                'level'  => ObjectModel::LEVEL_REGION,
                'active' => ObjectModel::STATUS_ACTIVE
            ])
            ->execute()
            ->toArray();

        $this->getLevelsRange(ObjectModel::LEVEL_REGION);

        //TODO пока оказалось не нужно. Если и в дальнейшем не будет нужно - удалить
        //$result['types'] = $this->getAvailableTypesByLevelsRange();

        return $result;
    }

    /**
     * Получение typeDesc по Type
     *
     * @param int $type
     * @return array
     */
    public function getTypeDescs(int $type): array
    {
        // Эти 2 типа у нас привязаны к одному лвлу и проставляются автоматически
        if (in_array($type, [ObjectManager::TYPE_COUNTY, ObjectManager::TYPE_DISTRICT])) {
            return [];
        }

        return ObjectTypeModel::query()
            ->columns(['id', 'name'])
            ->where('status = :active: and level in ({levels:array})', [
                'active' => ObjectTypeModel::STATUS_ACTIVE,
                'levels' => $this->mappingTypeToLevels[$type]
            ])
            ->execute()
            ->toArray();
    }

    /**
     * Get query data by globID or region and level
     *
     * @param string $globId
     * @param int $level
     * @return array
     */
    private function getQueryData(string $globId, int $level): array
    {
        if (is_numeric($globId)) {
            $this->searchField = 'region_id';
        }

        $this->getLevelsRange($level);

        return ObjectModel::query()
            ->columns(['glob_id', 'name'])
            ->where("{$this->searchField} = '{$globId}'")
            ->andWhere("level in ({$this->minLevel}, {$this->maxLevel})")
            ->andWhere("status = :active:", ['active' => ObjectModel::STATUS_ACTIVE])
            ->execute()->toArray();
    }

    /**
     * Получение допустимых типов сущностей для виджета на фронте
     *
     * @return array
     */
    private function getAvailableTypesByLevelsRange(): array
    {
        return ObjectTypeModel::query()
            ->columns(['id', 'name'])
            ->inWhere('level', [$this->minLevel, $this->maxLevel])
            ->execute()
            ->toArray();
    }

    /**
     * Получения тайтла для поля формы с текущими элементами
     *
     * @param string $level
     * @return string
     * @throws InvalidLevelValueException
     */
    private function getFieldTitleByLevel(string $level): string
    {
        switch ($level) {
            case ObjectModel::LEVEL_REGION:
                return 'Округ / Район';
                break;
            case ObjectModel::LEVEL_COUNTY:
                return 'Район';
                break;
            case ObjectModel::LEVEL_AREA:
                return 'Внутригородская территория / Населенный пункт';
                break;
            case ObjectModel::LEVEL_CITY:
                return 'Внутригородская территория / Населенный пункт';
                break;
            case ObjectModel::LEVEL_CITY_DISTRICT:
                return 'Населенный пункт / Улица';
                break;
            case ObjectModel::LEVEL_PLACE:
                return 'Населенный пункт / Улица';
                break;
            case ObjectModel::LEVEL_STREET:
                return 'Дополнительная территория';
                break;
            case ObjectModel::LEVEL_ADDITIONAL_TERRITORY:
                return 'Дополнительная территория';
                break;
            default:
                throw new InvalidLevelValueException("Level {$level} is Invalid");
                break;
        }
    }

    /**
     * Получаем новый ренж уровней для поиска(нужен если в текущей итерации не нашли чайлдов,
     * тогда выбираем новые уровни для поиска)
     *
     * @param int $level
     */
    private function getLevelsRange(int $level): void
    {
        switch ($level) {
            case ObjectModel::LEVEL_REGION:
                $this->minLevel = ObjectModel::LEVEL_COUNTY;
                $this->maxLevel = ObjectModel::LEVEL_COUNTY;
                break;
            case ObjectModel::LEVEL_AREA:
            case ObjectModel::LEVEL_CITY:
                $this->minLevel = ObjectModel::LEVEL_CITY_DISTRICT;
                $this->maxLevel = ObjectModel::LEVEL_PLACE;
                break;
            case ObjectModel::LEVEL_CITY_DISTRICT:
            case ObjectModel::LEVEL_PLACE:
                $this->minLevel = ObjectModel::LEVEL_STREET;
                $this->maxLevel = ObjectModel::LEVEL_ADDITIONAL_TERRITORY;
                break;
            case ObjectModel::LEVEL_STREET:
            case ObjectModel::LEVEL_ADDITIONAL_TERRITORY:
                $this->minLevel = ObjectModel::LEVEL_ADDITIONAL_TERRITORY;
                $this->maxLevel = ObjectModel::LEVEL_ADDITIONAL_TERRITORY_SUBJECT;
                break;
            default:
                $this->minLevel = ObjectModel::LEVEL_REGION;
                $this->maxLevel = ObjectModel::LEVEL_REGION;
        }
    }

    /**
     * Получение level по glob_id
     *
     * @param string $globId
     * @return int
     * @throws ObjectNotFoundException
     */
    private function getLevelByGlobId(string $globId): int
    {
        $field = 'glob_id';

        if (is_numeric($globId)) {
            $field = 'region_id';
        }

        /** @var ObjectModel $object */
        $object = ObjectModel::query()->where("{$field} = :glob_id: and status = :active:", [
            'glob_id' => $globId,
            'active'  => ObjectModel::STATUS_ACTIVE
        ])->execute()->getFirst();

        if (!$object) {
            throw new ObjectNotFoundException("Object with glob_id '{$globId}' Not Found");
        }

        return $object->level;
    }
}
