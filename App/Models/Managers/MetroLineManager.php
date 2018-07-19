<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 17:17
 */

namespace App\Models\Managers;

use App\Exceptions\ObjectNotFoundException;
use App\Interfaces\CrudObjectManagerInterface;
use App\Models\DB\MetroLineModel;
use App\Validators\MetroLineValidator;
use Phalcon\Mvc\Model;

/**
 * Модель манагер линии метро
 *
 * Class MetroLineManager
 * @package App\Models\Managers
 */
class MetroLineManager extends AbstractModelManager implements CrudObjectManagerInterface
{
    /**
     * @var string
     */
    public static $modelClass = MetroLineModel::class;

    /**
     * MetroLineManager constructor.
     */
    public function __construct()
    {
        $this->validator = new MetroLineValidator();
        parent::__construct();
    }

    /**
     * Получение всех линий метро
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->modelsManager
            ->createBuilder()
            ->from(['lines' => MetroLineModel::class])
            ->columns(['id', 'name', 'color', 'region_id', 'city_id'])
            ->getQuery()
            ->execute()
            ->toArray();
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
        $line = $this->findFirstByAttributes(['id' => $id]);
        if (!$line) {
            throw new ObjectNotFoundException("Metro Line With Id {$id} Not Found");
        }

        return $line;
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

        return $this->findOrCreateStrictException()->assign($data)->save();
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

        return $this->findOrCreateStrictException($id)->assign($data)->save();
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
        return $this->findOrCreateStrictException($id)->save(['status' => MetroLineModel::STATUS_ACTIVE]);
    }
}