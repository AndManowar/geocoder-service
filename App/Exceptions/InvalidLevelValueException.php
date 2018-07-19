<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 24.05.18
 * Time: 14:31
 */

namespace App\Exceptions;


class InvalidLevelValueException extends AbstractBaseException
{
    /**
     * InvalidLevelValueException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}