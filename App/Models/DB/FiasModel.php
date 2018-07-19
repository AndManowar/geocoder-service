<?php
namespace App\Models\DB;

use Phalcon\Mvc\Model;

/**
 * Class FiasModel
 * -------
 * Импортирования таблица из ФИАС-а
 * + Таблица создавалась с файлов ADDROB__.DBF (86 шт на момент импорта) базы ФИАС-а
 * + Официальное описание всех таблиц и колонок ФИАС (в формате .doc):
 * http://fias.nalog.ru/Docs/%D0%A1%D0%B2%D0%B5%D0%B4%D0%B5%D0%BD%D0%B8%D1%8F%20%D0%BE%20%D1%81%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%B5%20%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D0%B8%20%D0%A4%D0%98%D0%90%D0%A1%20%D1%81%2009062016.doc
 *
 * @package App\Models
 */
class FiasModel extends Model
{
    /**
     * Статус последней исторической записи в жизненном цикле адресного объекта:
     * 0 – Не актуальный
     * 1 - Актуальный
     *
     * @var integer
     * @Column(column="actstatus", type="integer", length=2, nullable=false)
     */
    public $actstatus;

    /**
     * Guid записи родительского объекта (улицы, города, населенного пункта и т.п.)
     *
     * @var string
     * @Column(column="aoguid", type="string", length=36, nullable=false)
     */
    public $aoguid;

    /**
     * Уникальный идентификатор записи. Ключевое поле.
     *
     * @var string
     * @Primary
     * @Column(column="aoid", type="string", length=36, nullable=false)
     */
    public $aoid;

    /**
     * Уровень вложенности адресного объекта
     *
     * @var integer
     * @Column(column="aolevel", type="integer", length=2, nullable=false)
     */
    public $aolevel;

    /**
     * Код района
     *
     * @var string
     * @Column(column="areacode", type="string", length=3, nullable=false)
     */
    public $areacode;

    /**
     * Код автономии
     *
     * @var string
     * @Column(column="autocode", type="string", length=1, nullable=false)
     */
    public $autocode;

    /**
     * Статус центра
     *
     * @var integer
     * @Column(column="centstatus", type="integer", length=2, nullable=false)
     */
    public $centstatus;

    /**
     * Код города
     *
     * @var string
     * @Column(column="citycode", type="string", length=3, nullable=false)
     */
    public $citycode;

    /**
     * Код адресного объекта одной строкой с признаком актуальности из КЛАДР
     *
     * @var string
     * @Column(column="code", type="string", length=17, nullable=false)
     */
    public $code;

    /**
     * Статус актуальности КЛАДР 4 (последние две цифры в коде)
     *
     * @var integer
     * @Column(column="currstatus", type="integer", length=2, nullable=false)
     */
    public $currstatus;

    /**
     * Окончание действия записи
     *
     * @var string
     * @Column(column="enddate", type="string", nullable=true)
     */
    public $enddate;

    /**
     * Формализованное наименование
     *
     * @var string
     * @Column(column="formalname", type="string", length=120, nullable=false)
     */
    public $formalname;

    /**
     * Код ИФНС ФЛ
     *
     * @var string
     * @Column(column="ifnsfl", type="string", length=4, nullable=false)
     */
    public $ifnsfl;

    /**
     * Код территориального участка ИФНС ФЛ
     *
     * @var string
     * @Column(column="ifnsul", type="string", length=4, nullable=false)
     */
    public $ifnsul;

    /**
     * Идентификатор записи  связывания с последующей исторической записью
     *
     * @var string
     * @Column(column="nextid", type="string", length=36, nullable=false)
     */
    public $nextid;

    /**
     * Официальное наименование
     *
     * @var string
     * @Column(column="offname", type="string", length=120, nullable=false)
     */
    public $offname;

    /**
     * OKATO
     *
     * @var string
     * @Column(column="okato", type="string", length=11, nullable=false)
     */
    public $okato;

    /**
     * OKTMO
     *
     * @var string
     * @Column(column="oktmo", type="string", length=11, nullable=false)
     */
    public $oktmo;

    /**
     * Статус действия над записью – причина появления записи (см. таблицу OperationStatuses из базы ФИАС-а )
     *
     * @var integer
     * @Column(column="operstatus", type="integer", length=2, nullable=false)
     */
    public $operstatus;

    /**
     * Идентификатор объекта родительского объекта
     *
     * @var string
     * @Column(column="parentguid", type="string", length=36, nullable=false)
     */
    public $parentguid;

    /**
     * Код населенного пункта
     *
     * @var string
     * @Column(column="placecode", type="string", length=3, nullable=false)
     */
    public $placecode;

    /**
     * Код адресного элемента одной строкой без признака актуальности (последних двух цифр)
     *
     * @var string
     * @Column(column="plaincode", type="string", length=15, nullable=false)
     */
    public $plaincode;

    /**
     * Почтовый индекс
     *
     * @var string
     * @Column(column="postalcode", type="string", length=6, nullable=false)
     */
    public $postalcode;

    /**
     * Идентификатор записи связывания с предыдушей исторической записью
     *
     * @var string
     * @Column(column="previd", type="string", length=36, nullable=false)
     */
    public $previd;

    /**
     * Код региона
     *
     * @var string
     * @Column(column="regioncode", type="string", length=2, nullable=false)
     */
    public $regioncode;

    /**
     * Краткое наименование типа объекта
     *
     * @var string
     * @Column(column="shortname", type="string", length=10, nullable=false)
     */
    public $shortname;

    /**
     * Начало действия записи
     *
     * @var string
     * @Column(column="startdate", type="string", nullable=true)
     */
    public $startdate;

    /**
     * Код улицы
     *
     * @var string
     * @Column(column="streetcode", type="string", length=4, nullable=false)
     */
    public $streetcode;

    /**
     * Код территориального участка ИФНС ФЛ
     *
     * @var string
     * @Column(column="terrifnsfl", type="string", length=4, nullable=false)
     */
    public $terrifnsfl;

    /**
     * Код территориального участка ИФНС ЮЛ
     *
     * @var string
     * @Column(column="terrifnsul", type="string", length=4, nullable=false)
     */
    public $terrifnsul;

    /**
     * Дата  внесения (обновления) записи
     *
     * @var string
     * @Column(column="updatedate", type="string", nullable=true)
     */
    public $updatedate;

    /**
     * Код внутригородского района
     *
     * @var string
     * @Column(column="ctarcode", type="string", length=3, nullable=false)
     */
    public $ctarcode;

    /**
     * Код дополнительного адресообразующего элемента
     *
     * @var string
     * @Column(column="extrcode", type="string", length=4, nullable=false)
     */
    public $extrcode;

    /**
     * Код подчиненного дополнительного адресообразующего элемента
     *
     * @var string
     * @Column(column="sextcode", type="string", length=3, nullable=false)
     */
    public $sextcode;

    /**
     * Статус актуальности адресного объекта ФИАС на текущую дату:
     * 0 – Не актуальный
     * 1 - Актуальный
     *
     * @var integer
     * @Column(column="livestatus", type="integer", length=2, nullable=false)
     */
    public $livestatus;

    /**
     * Внешний ключ на нормативный документ
     *
     * @var string
     * @Column(column="normdoc", type="string", length=36, nullable=false)
     */
    public $normdoc;

    /**
     * Код элемента планировочной структуры
     *
     * @var string
     * @Column(column="plancode", type="string", length=4, nullable=false)
     */
    public $plancode;

    /**
     * Кадастровый номер
     *
     * @var string
     * @Column(column="cadnum", type="string", length=100, nullable=false)
     */
    public $cadnum;

    /**
     * Тип деления:
     * 0 – не определено
     * 1 – муниципальное
     * 2 – административное
     *
     * @var integer
     * @Column(column="divtype", type="integer", length=1, nullable=false)
     */
    public $divtype;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'fias';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return FiasModel[]|FiasModel|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FiasModel|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
