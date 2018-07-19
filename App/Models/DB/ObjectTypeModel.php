<?php

namespace App\Models\DB;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Class ObjectTypeModel
 * -------
 * Краткое наименование типа объекта в зависимости от уровня
 *
 * @package App\Models\DB
 */
class ObjectTypeModel extends Model
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
     * FiasModel::$aolevel - Уровень вложенности адресного объекта
     *
     * @var integer
     * @Column(column="level", type="integer", length=6, nullable=false)
     */
    public $level;

    /**
     * Статус (активный, удален)
     *
     * @var integer
     * @Column(column="status", type="integer", length=4, nullable=false)
     */
    public $status;

    /**
     * FiasModel::$shortname - Краткое наименование типа объекта
     *
     * @var string
     * @Column(column="name", type="string", length=30, nullable=false)
     */
    public $name;

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
        return 'object_types';
    }
}
