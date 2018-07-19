<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 24.05.18
 * Time: 9:03
 */

namespace App\Validators;

use Phalcon\Validation;
use Phalcon\Validation\Message\Group;

/**
 * Class AbstractValidator
 * @package App\Validators
 */
abstract class AbstractValidator extends Validation
{
    /**
     * Validation errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     */
    public abstract function validateData(array $data): bool;

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param Validation\MessageInterface[] $messages
     */
    public function setDbErrors(array $messages)
    {
        /** @var Validation\MessageInterface $message */
        foreach ($messages as $message) {
            $this->errors[] = $message->getMessage();
        }
    }

    /**
     * Set validation errors
     *
     * @param Group $messages
     * @return void
     */
    protected function setErrors(Group $messages): void
    {
        foreach ($messages as $message) {
            $this->errors[] = $message->getMessage();
        }
    }
}