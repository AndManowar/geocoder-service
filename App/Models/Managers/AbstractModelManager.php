<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 25.05.18
 * Time: 12:48
 */

namespace App\Models\Managers;

use App\Exceptions\ObjectNotFoundException;
use App\Models\DB\ObjectModel;
use App\Validators\AbstractValidator;
use App\Validators\ObjectValidator;
use Topnlab\PhalconBase\ModelManager;

/**
 * Class AbstractModelManager
 * @package App\Models\Managers
 */
class AbstractModelManager extends ModelManager
{
    /**
     * @var string
     */
    protected static $modelClass = null;

    /**
     * @var AbstractValidator|ObjectValidator
     */
    protected $validator;

    /**
     * @return string
     */
    public function modelClass(): string
    {
        return static::$modelClass;
    }

    /**
     * Find model or create new
     *
     * @param int|null $id
     * @return ObjectModel
     * @throws ObjectNotFoundException
     */
    public function findOrCreateStrictException(int $id = null)
    {
        if ($id == null) {
            return new static::$modelClass;
        }

        /** @var ObjectModel $object */
        $object = $this->findFirstByAttributes(['id' => $id]);
        if (!$object) {
            throw new ObjectNotFoundException("Object with id {$id} not found!");
        }

        return $object;
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validator->getErrors();
    }
}