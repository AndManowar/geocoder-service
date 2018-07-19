<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 12:37
 */

namespace App\Models\DB;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Модель сущности - Метро
 *
 * Class MetroModel
 * @package App\Models\DB
 */
class MetroModel extends Model
{
    /**
     * Type status active
     *
     * @const
     */
    const STATUS_ACTIVE = 1;

    /**
     * Type status soft deleted
     *
     * @const
     */
    const STATUS_DELETED = 0;

    /**
     * Первичный ключ
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=6, nullable=false)
     */
    public $id;

    /**
     * Линия метро
     *
     * @var integer
     * @Column(column="metro_line_id", type="integer", length=6, nullable=false)
     */
    public $metro_line_id;

    /**
     * Статус (активный, удален)
     *
     * @var integer
     * @Column(column="status", type="integer", length=4, nullable=false)
     */
    public $status;

    /**
     * Название станции
     *
     * @var string
     * @Column(column="station_name", type="string", length=60, nullable=false)
     */
    public $station_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->addBehavior(new SoftDelete([
            'field' => 'status',
            'value' => self::STATUS_DELETED,
        ]));

        $this->hasMany('id',
            MetroKeywordModel::class,
            'metro_id',
            ['alias' => 'metro_keywords']
        );

        $this->hasOne('metro_line_id',
            MetroLineModel::class,
            'id',
            ['alias' => 'metro_line']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'metros';
    }

    /**
     * Relation
     *
     * @param null $parameters
     * @return Model\ResultsetInterface|MetroKeywordModel[]
     */
    public function getKeywords($parameters = null)
    {
        return $this->getRelated('metro_keywords', $parameters);
    }

    /**
     * Relation
     *
     * @param null $parameters
     * @return Model\ResultsetInterface|MetroLineModel
     */
    public function getMetroLine($parameters = null)
    {
        return $this->getRelated('metro_line', $parameters);
    }
}