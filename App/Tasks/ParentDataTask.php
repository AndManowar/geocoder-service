<?php

use Phalcon\Cli\Task;

/**
 * Class ParentDataTask
 *
 * @package App\Tasks
 * @author Alexey Yermakov slims.alex@gmail.com
 */
class ParentDataTask extends Task
{
    /**
     * Логирование выполнения запроса
     */
    private $logger;

    /**
     * Логирование выполнения запроса
     */
    private $logPath;

    /**
     * Билд парент даты
     *
     * @var array
     */
    private $parentData = [];

    /**
     * Время старта
     *
     * @var
     */
    private $timerStart = null;

    /**
     * Сбор данных и обновление parent_data
     */
    public function buildParentAction()
    {
        // Логирование
        $this->initLog('parent-data-build-new');
        $this->writeLog('Start action [build]: ' . date('d-m-y H:i'));

        $query = $this->db->query('SELECT objects.* 
            FROM objects 
            LEFT JOIN object_info ON object_info.object_id = objects.id
            WHERE objects.status = 1 and objects.level > 1 and object_info.parent_data is NULL
            ORDER BY objects.level, objects.id');

        while ($object = $query->fetch()) {
            // Собираем парентов
            $this->buildParentData($object['parent_glob_id']);

            // Записываем parent_data
            $queryUpdate = $this->db->query("UPDATE object_info set parent_data = '{$this->parentData}' WHERE object_id = " . $object['id']);

            // Проверка или обновились данные + запись в лог
            if (!$queryUpdate) {
                $this->writeLog('ERROR update ObjectInfo: ' . $object['id'] . ' Memory - ' . memory_get_usage() . ' - ' . memory_get_usage(true));
            } else {
                $this->writeLog('SUCCESS update ObjectInfo id: ' . $object['id'] . ' Memory - ' . memory_get_usage() . ' - ' . memory_get_usage(true));
            }
        }

        $this->writeLog('End action: ' . date('d - m - y H:i'));
        $this->closeLog();
    }

    /**
     * Пострение дерева по parent_glob_id
     *
     * @param string $parentGlobId
     * @return void
     */
    private function buildParentData(string $parentGlobId): void
    {
        // Очищаем parentData
        $this->parentData = [];

        $query = $this->db->query("SELECT 
            objects.id,
            objects.glob_id,
            objects.name,
            objects.full_name,
            objects.level,
            area_id,
            autonomy_id,
            city_id,
            city_district_id,
            place_id,
            street_id,
            external_id,
            parent_glob_id,
            region_id,
            type.name as type_name,
            info.parent_data
        FROM objects 
        LEFT JOIN object_info info ON info.object_id = objects.id
        LEFT JOIN object_types type ON type.id = type_id
        WHERE objects.status = 1 and objects.glob_id = '{$parentGlobId}'
        LIMIT 1");

        $query->setFetchMode(
            \Phalcon\Db::FETCH_ASSOC
        );

        $model = $query->fetch();

        if ($model['parent_data']) {
            $parent_data = json_decode($model['parent_data'], true);
            unset($model['parent_data']);
            $this->parentData = $parent_data;
        }

        $this->parentData[$model['level']] = $model;
        $this->parentData = json_encode(array_reverse($this->parentData, true), JSON_UNESCAPED_UNICODE);
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
    public function closeLog(): void
    {
        if (!is_null($this->timerStart)) {
            $this->writeLog('Finish importing in ' . (round((time() - $this->timerStart) / 60, 2)) . ' minutes');
        }
        fclose($this->logger);
    }
}
