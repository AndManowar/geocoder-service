<?php

namespace App\Models\DB;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Class ObjectModel
 * -------
 * Внимание:
 * Если поле из FiasModel модели, тогда формат описания:
 * Имя_Класа::имя_колонки - Описание из ФИАС документации
 * Для понимания откуда брались данные
 *
 * @property ObjectInfoModel $info
 * @package App\Models\DB
 */
class ObjectModel extends Model
{
    /**
     * Статусы записи
     *
     * @var integer
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 0;
    const STATUS_DELETED = -1;

    /**
     * Список уровней
     */
    const LEVEL_REGION = 1;                         // Регион
    const LEVEL_COUNTY = 2;                         // Округ
    const LEVEL_AREA = 3;                           // Район
    const LEVEL_CITY = 4;                           // Город
    const LEVEL_CITY_DISTRICT = 5;                  // Внутригородская территория
    const LEVEL_PLACE = 6;                          // Населенный пункт
    const LEVEL_STREET = 7;                         // Улица
    const LEVEL_ADDITIONAL_TERRITORY = 90;          // Дополнительная территория
    const LEVEL_ADDITIONAL_TERRITORY_SUBJECT = 91;  // Объект, подчиненный дополнительной территории

    /**
     * Первичный ключ
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=10, nullable=false)
     */
    public $id;

    /**
     * FiasModel::$aoid - Уникальный идентификатор записи
     *
     * @var string
     * @Column(column="glob_id", type="string", length=46, nullable=false)
     */
    public $glob_id;

    /**
     * FiasModel::$parentguid - Идентификатор объекта родительского объекта
     *
     * @var string
     * @Column(column="parent_glob_id", type="string", length=46, nullable=true)
     */
    public $parent_glob_id;

    /**
     * FiasModel::$formalname - Формализованное наименование
     *
     * @var string
     * @Column(column="name", type="string", length=250, nullable=false)
     */
    public $name;

    /**
     * FiasModel::$offname - Официальное наименование
     *
     * @var string
     * @Column(column="full_name", type="string", length=250, nullable=false)
     */
    public $full_name;

    /**
     * ObjectInfoModel::$id - Краткое наименование типа объекта (ObjectInfoModel::$name)
     *
     * @var integer
     * @Column(column="type_id", type="integer", length=6, nullable=false)
     */
    public $type_id;

    /**
     * FiasModel::$aolevel - Уровень вложенности адресного объекта
     *
     * @var integer
     * @Column(column="level", type="integer", length=6, nullable=false)
     */
    public $level;

    /**
     * FiasModel::$regioncode - Код региона
     *
     * @var integer
     * @Column(column="region_id", type="integer", length=6, nullable=false)
     */
    public $region_id;

    /**
     * FiasModel::$areacode - Код района
     *
     * @var integer
     * @Column(column="area_id", type="integer", length=6, nullable=false)
     */
    public $area_id;

    /**
     * FiasModel::$autocode - Код автономии
     *
     * @var integer
     * @Column(column="autonomy_id", type="integer", length=6, nullable=false)
     */
    public $autonomy_id;

    /**
     * FiasModel::$citycode - Код города
     *
     * @var integer
     * @Column(column="city_id", type="integer", length=6, nullable=false)
     */
    public $city_id;

    /**
     * FiasModel::$ctarcode - Код внутригородского района
     *
     * @var integer
     * @Column(column="city_district_id", type="integer", length=6, nullable=false)
     */
    public $city_district_id;

    /**
     * FiasModel::$placecode - Код населенного пункта
     *
     * @var integer
     * @Column(column="place_id", type="integer", length=6, nullable=false)
     */
    public $place_id;

    /**
     * FiasModel::$streetcode - Код улицы
     *
     * @var integer
     * @Column(column="street_id", type="integer", length=6, nullable=false)
     */
    public $street_id;

    /**
     * FiasModel::$extrcode - Код дополнительного адресообразующего элемента
     *
     * @var integer
     * @Column(column="external_id", type="integer", length=6, nullable=false)
     */
    public $external_id;

    /**
     * Статус
     *
     * @var integer
     * @Column(column="status", type="integer", length=4, nullable=false)
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasOne('id',
            ObjectInfoModel::class,
            'object_id',
            ['alias' => 'info']
        );

        $this->hasMany('id',
            FolkDistrictMappingModel::class,
            'formal_district_glob_id',
            ['alias' => 'folk_districts']
        );

        $this->addBehavior(new SoftDelete([
            'field' => 'status',
            'value' => self::STATUS_DELETED,
        ]));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'objects';
    }

    /**
     * Relation
     *
     * @param null $parameters
     * @return Model\ResultsetInterface
     */
    public function getInfo($parameters = null)
    {
        return $this->getRelated('info', $parameters);
    }

    /**
     * Relation
     *
     * @param null $parameters
     * @return Model\ResultsetInterface
     */
    public function getFolkDistricts($parameters = null)
    {
        return $this->getRelated('folk_districts', $parameters);
    }
}
