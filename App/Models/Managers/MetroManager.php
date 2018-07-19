<?php

namespace App\Models\Managers;

use App\Exceptions\ObjectNotFoundException;
use App\Interfaces\CrudObjectManagerInterface;
use App\Models\DB\MetroKeywordModel;
use App\Models\DB\MetroModel;
use App\Validators\MetroValidator;
use Phalcon\Mvc\Model;

/**
 * Менеджер модели метро
 *
 * Class MetroManager
 * @package App\Models\Managers
 */
class MetroManager extends AbstractModelManager implements CrudObjectManagerInterface
{
    /**
     * @var string
     */
    public static $modelClass = MetroModel::class;

    /**
     * MetroManager constructor.
     */
    public function __construct()
    {
        $this->validator = new MetroValidator();
        parent::__construct();
    }

    /**
     * Get record by id
     *
     * @param int $id
     * @return Model
     * @throws ObjectNotFoundException
     */
    public function get(int $id): Model
    {
        /** @var MetroModel $metro */
        $metro = $this->findFirstByAttributes(['id' => $id]);
        if (!$metro) {
            throw new ObjectNotFoundException("Metro With Id {$id} Not Found");
        }

        return $metro;
    }

    /**
     * Данных модели метро
     *
     * @param int $id
     * @return array
     * @throws ObjectNotFoundException
     */
    public function getWithRelations(int $id): array
    {
        /** @var MetroModel $metro */
        $metro = $this->get($id);
        return [
            'id'            => $metro->id,
            'metro_line_id' => $metro->metro_line_id,
            'line_name'     => $metro->getMetroLine()->name,
            'region_id'     => $metro->getMetroLine()->region_id,
            'city_id'       => $metro->getMetroLine()->city_id,
            'color'         => $metro->getMetroLine()->color,
            'station_name'  => $metro->station_name,
            'status'        => $metro->status,
            'keywords'      => $metro->getKeywords()->toArray()
        ];
    }

    /**
     * Create object method
     *
     * @param array $data
     * @return boolean
     * @throws ObjectNotFoundException
     */
    public function createObject(array $data): bool
    {
        if (!$this->validator->validateData($data)) {
            return false;
        }

        $this->db->begin();

        /** @var MetroModel $metro */
        $metro = $this->findOrCreateStrictException()->assign($data);

        if (!$metro->save() || !$this->createKeywords(['keywords' => $data['keywords'], 'metro_id' => $metro->id])) {
            $this->db->rollback();

            return false;
        }

        $this->db->commit();

        return true;
    }

    /**
     * Update object method
     *
     * @param int $id
     * @param array $data
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function updateObject(int $id, array $data): bool
    {
        if (!$this->validator->validateData($data)) {
            return false;
        }

        $this->db->begin();

        /** @var MetroModel $metro */
        $metro = $this->findOrCreateStrictException($id)->assign($data);

        // Так как по факту у нас релейшн М:М, то берем первую запись keywords и ее меняем
        if (!$metro->save() || !$this->updateKeywords($metro, $data)) {
            $this->db->rollback();

            return false;
        }

        $this->db->commit();

        return true;
    }

    /**
     * Object Soft delete
     *
     * @param int $id
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function delete(int $id): bool
    {
        return $this->findOrCreateStrictException($id)->delete();
    }

    /**
     * Recover soft deleted object
     *
     * @param int $id
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function recover(int $id): bool
    {
        return $this->findOrCreateStrictException($id)->save(['status' => MetroModel::STATUS_ACTIVE]);
    }

    /**
     * Создание объектов ключевых слов
     *
     * @param array $data
     * @return bool
     */
    private function createKeywords(array $data): bool
    {
        foreach (explode(',', $data['keywords']) as $keyword) {

            $keywordObject = new MetroKeywordModel();

            if (!$keywordObject->assign(['keyword' => trim($keyword), 'metro_id' => $data['metro_id']])->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Обновление кейвордов метро (добавление или удаление, перезапись значений)
     *
     * @param MetroModel $metroModel
     * @param array $data
     * @return bool
     */
    private function updateKeywords(MetroModel $metroModel, array $data):bool
    {
        // Массив текущих кейвордов с которыми будем работать после добавления или удаления
        $currentKeywords = [];
        $newKeywords = explode(',', $data['keywords']); // кейворды, присланные с формы
        // Каунты тех и других
        $newKeywordsCount = count($newKeywords);
        $oldKeywordsCount = count($metroModel->getKeywords());

        // Удаляем до нужного нового количества
        if ($newKeywordsCount < $oldKeywordsCount) {
            if (!$this->deleteKeywordsModels($metroModel, $oldKeywordsCount - $newKeywordsCount)) {
                return false;
            }
            // Получаем новые кейворды для работы по связи после удаления
            $currentKeywords = $metroModel->getKeywords();
        } else if ($newKeywordsCount > $oldKeywordsCount) { // Досоздаем пустышки до нужного нового количества
            $currentKeywords = $this->createTemplateKeywordModel($metroModel, $newKeywordsCount);
        }

        /** @var MetroKeywordModel $keyword */
        // Записываем новые значения в модели и сохраняем
        foreach ($currentKeywords as $id => $keyword) {
            // Если значение кейворда = значению с формы - пропускаем
            if ($keyword->keyword === trim($newKeywords[$id])) {
                continue;
            }

            if (!$keyword->assign(['keyword' => trim($newKeywords[$id]), 'metro_id' => $metroModel->id])->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Удаление моделей кейвордов, если количество новых < количества старых
     *
     * @param MetroModel $metroModel
     * @param int $countToDelete
     * @return bool
     */
    private function deleteKeywordsModels(MetroModel $metroModel, int $countToDelete): bool
    {
        for ($i = 0; $i < $countToDelete; $i++) {
            if (!$metroModel->getKeywords()[$i]->delete()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Создаем пустышки моделей кейвордов до нужного нового количества, присланного с формы при апдейте
     *
     * @param MetroModel $metroModel
     * @param int $countToCreate
     * @return array
     */
    private function createTemplateKeywordModel(MetroModel $metroModel, int $countToCreate): array
    {
        $keywords = $metroModel->getKeywords();
        $result = [];

        for ($i = 0; $i < $countToCreate; $i++) {
            if (isset($keywords[$i])) {
                $result[] = $keywords[$i];
                continue;
            }
            $result [] = new MetroKeywordModel();
        }

        return $result;
    }
}