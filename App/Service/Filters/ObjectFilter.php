<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 18.06.18
 * Time: 16:51
 */

namespace App\Service\Filters;

use App\Models\DB\ObjectInfoModel;
use App\Models\DB\ObjectModel;
use App\Models\Managers\ObjectManager;

/**
 * Class FilterService
 * @package App\Service
 */
class ObjectFilter extends AbstractFilterService
{
    /**
     * @var string
     */
    public static $modelClass = ObjectModel::class;

    /**
     * @var array
     */
    protected $queryColumns = [
        'id',
        'region_id',
        'glob_id',
        'name',
        "json_extract(parent_data, '$**.name')  as parents_names",  // Достаем парентов для полей админки
        "json_extract(parent_data, '$**.level')  as parents_levels", // Достаем уровни парентов, чтобы выписать их в правильные поля грида
        'is_edited',
    ];

    /**
     * @var array
     */
    protected $joins = [
        [
            'model'     => ObjectInfoModel::class,
            'condition' => 'id = object_id',
            'alias'     => 'info'
        ]
    ];

    /**
     * Инициализация параметров фильтрации и пагинации
     */
    public function __construct()
    {
        $this->modelManager = new ObjectManager();

        $this->filterParams = [
            'region_id'        => self::OPERATOR_EQUALS,
            'county_id'        => self::OPERATOR_EQUALS,
            'city_id'          => self::OPERATOR_EQUALS,
            'city_district_id' => self::OPERATOR_EQUALS,
            'name'             => self::OPERATOR_MATCH,
            'type'             => self::OPERATOR_IN
        ];
    }
}
