<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 28.05.18
 * Time: 11:19
 */

namespace App\Exceptions;

/**
 * Class InvalidParentDataException
 * @package App\Exceptions
 */
class InvalidParentDataException extends AbstractBaseException
{
    /**
     * InvalidParentDataException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}