<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 18.05.18
 * Time: 14:11
 */

namespace App\Interfaces;

use App\Exceptions\ObjectNotFoundException;
use Phalcon\Mvc\Model;

/**
 * CRUD methods for object model manager implementator
 *
 * Interface CrudObjectManagerInterface
 * @package App\Interfaces
 */
interface CrudObjectManagerInterface
{
    /**
     * Get record by id
     *
     * @param int $id
     * @return Model
     * @throws ObjectNotFoundException
     */
    public function get(int $id): Model;

    /**
     * Create object method
     *
     * @param array $data
     * @return boolean
     */
    public function createObject(array $data): bool;

    /**
     * Update object method
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateObject(int $id, array $data): bool;

    /**
     * Object Soft delete
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}