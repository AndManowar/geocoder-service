<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 12:00
 */

namespace App\Models\Managers;
use App\Exceptions\ObjectNotFoundException;
use App\Interfaces\CrudObjectManagerInterface;
use App\Models\DB\DirectionModel;
use App\Validators\DirectionValidator;
use Phalcon\Mvc\Model;

/**
 * Менеджер модели направлений
 *
 * Class DirectionManager
 * @package App\Models\Managers
 */
class DirectionManager extends AbstractModelManager implements CrudObjectManagerInterface
{
    /**
     * @var string
     */
    public static $modelClass = DirectionModel::class;

    /**
     * DirectionManager constructor.
     */
    public function __construct()
    {
        $this->validator = new DirectionValidator();
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
        $direction = $this->findFirstByAttributes(['id' => $id]);
        if (!$direction) {
            throw new ObjectNotFoundException("Direction With Id {$id} Not Found");
        }

        return $direction;
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
        return $this->findOrCreateStrictException($id)->save(['status' => DirectionModel::STATUS_ACTIVE]);
    }
}