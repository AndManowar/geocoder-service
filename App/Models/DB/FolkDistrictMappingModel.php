<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 06.06.18
 * Time: 13:10
 */

namespace App\Models\DB;

use Phalcon\Mvc\Model;

/**
 * Модель мапинга народных районов с формальными
 *
 * Class FolkDistrictMapModel
 * @package App\Models\DB
 */
class FolkDistrictMappingModel extends Model
{
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
     * Глоб айди формального района
     *
     * @var integer
     * @Column(column="formal_district_glob_id", type="string", length=46, nullable=false)
     */
    public $formal_district_glob_id;

    /**
     * Глоб айди народного района
     *
     * @var integer
     * @Column(column="folk_district_glob_id", type="string", length=46, nullable=false)
     */
    public $folk_district_glob_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("geocoder");

        $this->belongsTo('formal_district_glob_id',
            ObjectModel::class,
            'glob_id',
            ['alias' => 'formal_district']
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'folk_districts_mapping';
    }

    /**
     * @param null $parameters
     * @return Model\ResultsetInterface
     */
    public function getFormalDistrict($parameters = null)
    {
        return $this->getRelated('formal_district', $parameters);
    }
}