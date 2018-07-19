<?php
namespace App\Models\DB;

use Phalcon\Mvc\Model;

/**
 * Class ObjectInfoModel
 * -------
 * Внимание:
 * Если поле из FiasModel модели, тогда формат описания:
 * Имя_Класа::имя_колонки - Описание из ФИАС документации
 * Для понимания откуда брались данные
 *
 * @property ObjectModel $object
 * @package App\Models\DB
 */
class ObjectInfoModel extends Model
{
    /**
     * ObjectModel::$id - Первичный ключ
     *
     * @var integer
     * @Primary
     * @Column(column="object_id", type="integer", length=11, nullable=false)
     */
    public $object_id;

    /**
     * FiasModel::$aoid - Уникальный идентификатор записи
     *
     * @var string
     * @Column(column="fias_entry_id", type="string", length=46, nullable=false)
     */
    public $fias_entry_id;

    /**
     * FiasModel::$code - Код адресного объекта одной строкой с признаком актуальности из КЛАДР
     *
     * @var string
     * @Column(column="kladr", type="string", length=32, nullable=true)
     */
    public $kladr;

    /**
     * FiasModel::$postalcode - Почтовый индекс
     *
     * @var integer
     * @Column(column="postcode", type="integer", length=6, nullable=false)
     */
    public $postcode;

    /**
     * Ид элемента в справочниках emls (улица, населенный пункт)
     *
     * @var integer
     * @Column(column="emls_id", type="integer", length=6, nullable=false)
     */
    public $emls_id;

    /**
     * Есть ли элемент в справочниках емлс
     * 0 - есть
     * 1 - нет
     *
     * @var integer
     * @Column(column="emls_not_found", type="integer", length=2, nullable=true)
     */
    public $emls_not_found;

    /**
     * Или редактировался, поле чисто для админских целей, в логике не использется
     *
     * @var integer
     * @Column(column="is_edited", type="integer", length=2, nullable=true)
     */
    public $is_edited;

    /**
     * Денормализованные данные о всех предках элемента в виде json (чтобы не дергать рекурсивно базу для получения инфы о предках)
     *
     * @var string
     * @Column(column="parent_data", type="string", nullable=true)
     */
    public $parent_data;

    /**
     * FiasModel::$enddate - Окончание действия записи
     *
     * @var string
     * @Column(column="end_date", type="string", nullable=true)
     */
    public $end_date;

    /**
     * Пометка для народного района
     *
     * @var integer
     * @Column(column="is_folk_district", type="integer", length=2, nullable=true)
     */
    public $is_folk_district;

    /**
     *
     */
    public function initialize()
    {
        $this->belongsTo('object_id',
            ObjectModel::class,
            'id',
            ['alias' => 'object_model']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'object_info';
    }

    /**
     * @param null $parameters
     * @return Model\ResultsetInterface
     */
    public function getObject($parameters = null)
    {
        return $this->getRelated('object_model', $parameters);
    }
}
