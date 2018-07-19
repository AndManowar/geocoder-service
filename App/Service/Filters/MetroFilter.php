<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 12:50
 */

namespace App\Service\Filters;

use App\Models\DB\MetroKeywordModel;
use App\Models\DB\MetroLineModel;
use App\Models\DB\MetroModel;
use App\Models\Managers\MetroManager;

/**
 * Фильтратор модели метро
 *
 * Class MetroFilter
 * @package App\Service\Filters
 */
class MetroFilter extends AbstractFilterService
{
    /**
     * @var string
     */
    public static $modelClass = MetroModel::class;

    /**
     * Не объекта = не филтруем по левелу/типу
     *
     * @var bool
     */
    protected $isObject = false;

    /**
     * Колонки для выборки
     *
     * @var array
     */
    protected $queryColumns = [
        'model.id',
        'station_name',
        'name as line_name',
        'color as line_color',
        // Связь 1 ко многим. Получаем через запятую значения
        'GROUP_CONCAT_SEPARATOR(metro_keywords.keyword, \', \') as keywords',
        'region_id',
        'city_id'
    ];

    /**
     * @var array
     */
    protected $joins = [
        [
            'model'     => MetroKeywordModel::class,
            'condition' => 'model.id = metro_id',
            'alias'     => 'metro_keywords'
        ],
        [
            'model'     => MetroLineModel::class,
            'condition' => 'metro_line_id = metro_line.id',
            'alias'     => 'metro_line'
        ],
    ];

    /**
     * @var array
     */
    protected $groupBy = ['model.id'];

    /**
     * Инициализация параметров фильтрации и пагинации
     */
    public function __construct()
    {
        $this->modelManager = new MetroManager();

        $this->filterParams = [
            'region_id'     => self::OPERATOR_EQUALS,
            'city_id'       => self::OPERATOR_EQUALS,
            'metro_line_id' => self::OPERATOR_EQUALS,
            'station_name'  => self::OPERATOR_MATCH,
        ];
    }
}