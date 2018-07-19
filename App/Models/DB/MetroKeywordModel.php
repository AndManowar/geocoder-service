<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 17:13
 */

namespace App\Models\DB;

use Phalcon\Mvc\Model;

/**
 * Ключевые слова метро
 *
 * Class KeywordModel
 * @package App\Models\DB
 */
class MetroKeywordModel extends Model
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
     * Метро
     *
     * @var integer
     * @Column(column="metro_id", type="integer", length=6, nullable=false)
     */
    public $metro_id;

    /**
     * Ключевые слова
     *
     * @var string
     * @Column(column="keyword", type="string", length=100, nullable=false)
     */
    public $keyword;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'metro_keywords';
    }
}