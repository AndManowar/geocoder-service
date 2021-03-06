<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 17:10
 */

namespace App\Models\DB;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Модель линии метро
 *
 * Class MetroLineModel
 * @package App\Models\DB
 */
class MetroLineModel extends Model
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
     * Регион
     *
     * @var integer
     * @Column(column="region_id", type="integer", length=6, nullable=false)
     */
    public $region_id;

    /**
     * Район
     *
     * @var integer
     * @Column(column="city_id", type="integer", length=6, nullable=false)
     */
    public $city_id;

    /**
     * Статус (активный, удален)
     *
     * @var integer
     * @Column(column="status", type="integer", length=4, nullable=false)
     */
    public $status;

    /**
     * Название
     *
     * @var string
     * @Column(column="name", type="string", length=60, nullable=false)
     */
    public $name;

    /**
     * Цвет
     *
     * @var string
     * @Column(column="color", type="string", length=60, nullable=false)
     */
    public $color;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
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
        return 'metro_lines';
    }
}