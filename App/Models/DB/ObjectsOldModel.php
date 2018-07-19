<?php
namespace App\Models\DB;

use Phalcon\Mvc\Model;

/**
 * Старая таблица изпользуемая top&lab (если не будет использоваться, удалить...)
 * Class ObjectsOldModel
 *
 * @package App\Models\DB
 */
class ObjectsOldModel extends Model
{
    /**
     *
     * @var string
     * @Column(column="ID", type="string", length=100, nullable=true)
     */
    public $iD;

    /**
     *
     * @var string
     * @Column(column="globID", type="string", length=100, nullable=false)
     */
    public $globID;

    /**
     *
     * @var string
     * @Column(column="parent", type="string", length=100, nullable=true)
     */
    public $parent;

    /**
     *
     * @var string
     * @Column(column="name", type="string", length=250, nullable=true)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(column="fullName", type="string", length=250, nullable=true)
     */
    public $fullName;

    /**
     *
     * @var string
     * @Column(column="typeDesc", type="string", length=250, nullable=true)
     */
    public $typeDesc;

    /**
     *
     * @var integer
     * @Column(column="level", type="integer", length=6, nullable=true)
     */
    public $level;

    /**
     *
     * @var integer
     * @Column(column="region", type="integer", length=11, nullable=true)
     */
    public $region;

    /**
     *
     * @var integer
     * @Column(column="area", type="integer", length=11, nullable=true)
     */
    public $area;

    /**
     *
     * @var integer
     * @Column(column="autonomyID", type="integer", length=11, nullable=true)
     */
    public $autonomyID;

    /**
     *
     * @var integer
     * @Column(column="cityID", type="integer", length=11, nullable=true)
     */
    public $cityID;

    /**
     *
     * @var integer
     * @Column(column="cityDistriktID", type="integer", length=11, nullable=true)
     */
    public $cityDistriktID;

    /**
     *
     * @var integer
     * @Column(column="placeID", type="integer", length=11, nullable=true)
     */
    public $placeID;

    /**
     *
     * @var integer
     * @Column(column="streetID", type="integer", length=11, nullable=true)
     */
    public $streetID;

    /**
     *
     * @var integer
     * @Column(column="externalID", type="integer", length=11, nullable=true)
     */
    public $externalID;

    /**
     *
     * @var integer
     * @Column(column="externalChildID", type="integer", length=11, nullable=true)
     */
    public $externalChildID;

    /**
     *
     * @var integer
     * @Column(column="isCenter", type="integer", length=6, nullable=true)
     */
    public $isCenter;

    /**
     *
     * @var integer
     * @Column(column="IFNSFL", type="integer", length=11, nullable=true)
     */
    public $iFNSFL;

    /**
     *
     * @var integer
     * @Column(column="IFNSUL", type="integer", length=11, nullable=true)
     */
    public $iFNSUL;

    /**
     *
     * @var integer
     * @Column(column="OKATO", type="integer", length=11, nullable=true)
     */
    public $oKATO;

    /**
     *
     * @var integer
     * @Column(column="OKTMO", type="integer", length=11, nullable=true)
     */
    public $oKTMO;

    /**
     *
     * @var integer
     * @Column(column="postCode", type="integer", length=11, nullable=true)
     */
    public $postCode;

    /**
     *
     * @var string
     * @Column(column="endDate", type="string", nullable=true)
     */
    public $endDate;

    /**
     *
     * @var string
     * @Column(column="KLADR", type="string", length=32, nullable=true)
     */
    public $kLADR;

    /**
     *
     * @var integer
     * @Column(column="is_edited", type="integer", length=4, nullable=false)
     */
    public $is_edited;

    /**
     *
     * @var integer
     * @Column(column="emls_id", type="integer", length=12, nullable=false)
     */
    public $emls_id;

    /**
     *
     * @var integer
     * @Column(column="emls_not_found", type="integer", length=3, nullable=false)
     */
    public $emls_not_found;

    /**
     *
     * @var string
     * @Column(column="parent_data", type="string", nullable=true)
     */
    public $parent_data;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("geocoder");
        $this->setSource("objects_old");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'objects_old';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ObjectsOldModel[]|ObjectsOldModel|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ObjectsOldModel|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
