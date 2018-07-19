<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 01.06.18
 * Time: 9:57
 */

namespace App\Exceptions;

/**
 * Class FieldNotFoundException
 * @package App\Exceptions
 */
class FieldNotFoundException extends AbstractBaseException
{
    /**
     * FieldNotFoundException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 404)
    {
        parent::__construct($message, $code);
    }
}