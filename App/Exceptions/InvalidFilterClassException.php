<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 19.06.18
 * Time: 9:36
 */

namespace App\Exceptions;

/**
 * Class InvalidFilterClassException
 * @package App\Exceptions
 */
class InvalidFilterClassException extends AbstractBaseException
{
    /**
     * InvalidFilterClassException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}