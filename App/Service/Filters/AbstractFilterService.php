<?php

namespace App\Service\Filters;

use App\Exceptions\InvalidFilterClassException;
use App\Models\DB\ObjectModel;
use App\Models\Managers\AbstractModelManager;
use App\Models\Managers\ObjectManager;
use Phalcon\Mvc\ModelInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

/**
 * Абстрактный класс для фильтрации и пагинации
 *
 * Class AbstractFilterService
 * @package App\Service\Filters
 */
abstract class AbstractFilterService
{
    /**
     * Имя параметра с типом сущности
     *
     * @const
     */
    const PARAMS_TYPE_KEY = 'type';

    /**
     * Дефолтное значение кол-ва записей на странице
     *
     * @const
     */
    const DEFAULT_PAGE_SIZE = 25;

    /**
     * Оператор для фильтрации "="
     *
     * @const
     */
    const OPERATOR_EQUALS = '=';

    /**
     * Оператор для фильтрации "LIKE"
     *
     * @const
     */
    const OPERATOR_MATCH = 'MATCH';

    /**
     * MATCH(name) AGAINST('*Перв*' IN BOOLEAN MODE)
     *
     * Оператор для фильтрации in ()
     *
     * @const
     */
    const OPERATOR_IN = 'IN';

    /**
     * Имплементируемый каждой моделью интерфейс для проверки класса модели
     *
     * @const
     */
    const MODEL_INTERFACE = ModelInterface::class;

    /**
     * Уровни из массива маппинга для фильтрации объектов по уровням
     * По дефолту установлен уроверь Округа
     *
     * @var array
     */
    private $type = [
        ObjectModel::LEVEL_COUNTY
    ];

    /**
     * Массив полей, вытягиваемых из БД для пагинации
     *
     * @var array
     */
    protected $queryColumns;

    /**
     * Массив джоинов модели
     *
     * @var array
     */
    protected $joins;

    /**
     * Поля Group By
     *
     * @var array
     */
    protected $groupBy;

    /**
     * Маппинг типов сущностей к их уровням
     *
     * @var array
     */
    private $mappingTypeToLevels = [
        ObjectManager::TYPE_COUNTY        => [
            ObjectModel::LEVEL_COUNTY
        ],
        ObjectManager::TYPE_DISTRICT      => [
            ObjectModel::LEVEL_AREA
        ],
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
     * Параметры пагинации (текущая страница и кол-во записей на страницу)
     * Предустановлены дефолтные значения
     *
     * @var array
     */
    private $paginationParams = [
        'size' => self::DEFAULT_PAGE_SIZE,
        'page' => 1
    ];

    /**
     * Модель менеджер текущей модели
     *
     * @var AbstractModelManager
     */
    protected $modelManager = null;

    /**
     * Текущая модель для фильтрации
     *
     * @var ModelInterface
     */
    protected static $modelClass = null;

    /**
     * Возможные парметры для фильтрации и пагинации
     *
     * @var array
     */
    protected $filterParams;

    /**
     * Параметры фильтрации текущего запроса
     *
     * @var array
     */
    protected $params;

    /**
     * Если объект - применяем фильтры по левелу/типу и т д
     *
     * @var bool
     */
    protected $isObject = true;

    /**
     * Получение отсортированной и постранично разбитой даты
     *
     * @param array $params
     * @return array
     * @throws InvalidFilterClassException
     */
    public function getPaginatedAndSortedData(array $params): array
    {
        $this->parseParams($params);
        $queryData = $this->buildQuery();

        $result = [
            'items'      => $queryData['query'],
            'page'       => $this->paginationParams['page'],
            'pageSize'   => $this->paginationParams['size'],
            'totalPages' => ceil($queryData['totalCount'] / $this->paginationParams['size']),
            'totalCount' => $queryData['totalCount']
        ];

        return $result;
    }

    /**
     * Получение доступных для фильтрации параметров из адресной строки
     *
     * @param array $params
     */
    private function parseParams(array $params): void
    {
        if (!isset($params[self::PARAMS_TYPE_KEY]) && $this->isObject) {
            throw new InvalidTypeException("Entity type must be set");
        }

        foreach ($params as $key => $param) {
            /*
             * Если текущий параметр - тип сущности,
             * то заменяем его параметром для поиска - level
             */
            if ($key === self::PARAMS_TYPE_KEY) {
                $this->type = $this->mappingTypeToLevels[$param];
                unset($params[$key]);
                continue;
            }

            // Получаем параметры фильтрации
            if (in_array($key, array_keys($this->filterParams))) {
                $this->params[$key] = [
                    'operator' => $this->filterParams[$key],
                    'value'    => $param
                ];
            }

            // Получаем параметры пагинации
            if (in_array($key, array_keys($this->paginationParams))) {
                $this->paginationParams[$key] = $param;
            }
        }
    }

    /**
     * Получить отфильтрованную инфу и общее количество записей по фильтру
     *
     * @return array
     * @throws InvalidFilterClassException
     */
    private function buildQuery(): array
    {
        // Если модел класс не имплементирует ModelInterface
        if (!in_array(self::MODEL_INTERFACE, class_implements(static::$modelClass))) {
            throw new InvalidFilterClassException("Class Is Invalid");
        }

        $conditions = [];
        $binds = [];

        if ($this->isObject) {
            // Начальные условия поиска (по типу сущности)
            $conditions[] = 'level IN ({level:array})';
            $binds['level'] = $this->type;
        }

        // Формирование массива условий и биндов
        foreach ($this->params as $paramName => $param) {
            $conditions[] = $this->generateQueryString($paramName, $param['operator']);
            $binds[$paramName] = $param['operator'] == self::OPERATOR_MATCH ? '*' . $param['value'] . '*' : $param['value'];
        }

        $query = $this->modelManager->modelsManager->createBuilder()
            ->from(['model' => static::$modelClass])
            ->where('model.status = :status:', ['status' => 1]);

        if ($conditions) {
            $conditions = implode(' AND ', $conditions);
            $query->andwhere($conditions, $binds);
        }

        // собираем джоины
        foreach ($this->joins as $join) {
            $query->join($join['model'], $join['condition'], $join['alias']);
        }

        // Собираем GROUP BY
        foreach ($this->groupBy as $groupByValue) {
            $query->groupBy($groupByValue);
        }

        /*
         * Чучуть костыль =/
         *
         * Если ищем объект геокодера - делаем такой каунт (быстрее)
         * Потому что при фильтрации объектов не нужен join, а иначе нужен (спс фалькон)
         */
        if ($this->isObject) {
            $queryCount = static::$modelClass::count([
                'status = 1 and ' . $conditions,
                'bind' => $binds
            ]);
        } else {
            $queryCount = (clone $query)
                ->columns('count(DISTINCT model.id) as count')
                ->getQuery()
                ->execute()
                ->toArray()[0]['count'];
        }

        return [
            'query'      => $query
                ->columns($this->queryColumns)
                ->limit($this->paginationParams['size'], ($this->paginationParams['page'] - 1) * $this->paginationParams['size'])
                ->getQuery()
                ->execute()
                ->toArray(),
            'totalCount' => $queryCount
        ];
    }

    /**
     * Получить строку для подстановки в запрос для фильтрации
     *
     * @param string $param
     * @param string $operator
     * @return string
     */
    private function generateQueryString(string $param, string $operator): string
    {
        return $operator === self::OPERATOR_MATCH
            ? "MATCH_AGAINST({$param}, :{$param}:)"
            : "{$param} {$operator} :{$param}:";
    }
}
