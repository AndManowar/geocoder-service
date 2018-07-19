<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 25.05.18
 * Time: 9:09
 */

namespace App\Models\Managers;

use App\Exceptions\ObjectNotFoundException;
use App\Interfaces\CrudObjectManagerInterface;
use App\Models\DB\ObjectTypeModel;
use App\Validators\ObjectTypeValidator;
use Phalcon\Mvc\Model;

/**
 * Class TypeDescManager
 * @package App\Models\Managers
 */
class ObjectTypeManager extends AbstractModelManager implements CrudObjectManagerInterface
{
    /**
     * @var string
     */
    public static $modelClass = ObjectTypeModel::class;

    /**
     * TypeDescManager constructor.
     */
    public function __construct()
    {
        $this->validator = new ObjectTypeValidator();
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
        $type = $this->findFirstByAttributes(['id' => $id]);
        if (!$type) {
            throw new ObjectNotFoundException("Type With Id {$id} Not Found");
        }

        return $type;
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
     * Recover soft deleted object type
     *
     * @param int $id
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function recover(int $id): bool
    {
        return $this->findOrCreateStrictException($id)->save(['status' => ObjectTypeModel::STATUS_ACTIVE]);
    }
}