<?php
/********************************
 * Created by GoldenEye.
 * copyright 2010 - 2018
 ********************************/
use App\Models\DB\FiasModel;
use App\Models\DB\ObjectInfoModel;
use App\Models\DB\ObjectModel;
use App\Models\DB\ObjectTypeModel;
use Phalcon\Cli\Task;

/**
 * Class ImportTask
 *
 * @package App\Tasks
 * @author Alexey Yermakov slims.alex@gmail.com
 */
class ImportTask extends Task
{
    /**
     * Шаг выборки данных с базы ФИАС-а, php memory_limit: 1gb
     *
     * @var string
     */
    const SELECT_LIMIT = 100000;

    /*
     * Логирование выполнения запроса
     */
    private $logger;

    /*
    * Логирование выполнения запроса
    */
    private $logPath;

    /**
     * @var
     */
    private $timerStart = null;

    public function typesAction()
    {
        $types = FiasModel::find([
            'columns' => 'DISTINCT shortname as name, aolevel as level',
            'order' => 'level'
        ])->toArray();

        echo 'Start action [types]:' . PHP_EOL . 'Total count: ' . count($types) . PHP_EOL;

        foreach ($types as $id => $type) {

            if (!$search = ObjectTypeModel::find(['name = :name: AND level = :level:', 'bind' => $type])->toArray()) {

                $addType = new ObjectTypeModel($type);
                echo ($addType->save() ? 'New type successfully added.' : join(',', $addType->getMessages())) . PHP_EOL;

            } else {
                echo 'Type already exist.' . PHP_EOL;
            }
        }
        echo 'End action' . PHP_EOL;
    }

    public function dataAction()
    {
        echo 'Start action [data]:' . PHP_EOL;

        $offset = 0;

        $this->initLog('import-data');

        while (($objects = $this->modelsManager->createBuilder()->columns(['fias.*', 'ot.id as type_id'])
                ->addFrom(FiasModel::class, 'fias')
                ->innerJoin(ObjectTypeModel::class, "fias.aolevel = ot.level and fias.shortname = ot.name", "ot")
                ->limit(self::SELECT_LIMIT, $offset * self::SELECT_LIMIT + 1)
                ->getQuery()
                ->execute()
                ->toArray()) > 0
        ) {
            foreach ($objects as $id => $object) {

                $createObject = new ObjectModel([
                    'glob_id' => $object->fias->aoguid,
                    'parent_glob_id' => $object->fias->parentguid,
                    'name' => $object->fias->formalname,
                    'full_name' => $object->fias->offname,
                    'type_id' => intval($object->type_id), // id из ObjectTypeModel
                    'level' => $object->fias->aolevel,
                    'region_id' => $object->fias->regioncode,
                    'area_id' => intval($object->fias->areacode),
                    'autonomy_id' => intval($object->fias->autocode),
                    'city_id' => intval($object->fias->citycode),
                    'city_district_id' => intval($object->fias->ctarcode),
                    'place_id' => intval($object->fias->placecode),
                    'street_id' => intval($object->fias->streetcode),
                    'external_id' => intval($object->fias->extrcode),
                    'status' => ObjectModel::STATUS_ACTIVE,
                ]);

                if (!$createObject->save()) {
                    $this->writeLog('ERROR createObject: ' . join(',', $createObject->getMessages()));
                } else {

                    $this->writeLog('SUCCESS createObject: ' . $createObject->id);

                    $createObjectInfo = new ObjectInfoModel([
                        'object_id' => $createObject->id,
                        'fias_entry_id' => $object->fias->aoid,
                        'kladr' => $object->fias->code,
                        'postcode' => $object->fias->postalcode,
                        'emls_id' => 0,
                        'emls_not_found' => 0,
                        'is_edited' => 0,
                        'end_date' => $object->fias->enddate,
                    ]);

                    if (!$createObjectInfo->save()) {
                        $this->writeLog('ERROR createObjectInfo: ' . join(',', $createObjectInfo->getMessages()));
                    } else {
                        $this->writeLog('SUCCESS createObjectInfo for id: ' . $createObjectInfo->object_id);
                    }
                }
            }
            $offset++;
        }
        echo 'End action' . PHP_EOL;
        $this->closeLog();
    }

    /**
     * Старт логирования.
     *
     * @param string $log_file_name
     * @return void
     */
    private function initLog(string $log_file_name): void
    {
        $this->timerStart = time();
        $this->logPath = realpath(dirname(__FILE__) . '/../..') . '/' . $log_file_name . '.log';
        $this->logger = fopen($this->logPath, 'a');
    }

    /**
     * Запись в лог
     *
     * @param string $message
     * @return void
     */
    private function writeLog(string $message): void
    {
        $message .= PHP_EOL;
        echo $message;
        fwrite($this->logger, '[' . date('d.m.Y H:i:s') . '] ' . $message);
    }

    /**
     * Финиш логирования.
     */
    public function closeLog()
    {
        if (!is_null($this->timerStart)) {
            $this->writeLog('Finish importing in ' . (round((time() - $this->timerStart) / 60, 2)) . ' minutes');
        }
        fclose($this->logger);
    }
}