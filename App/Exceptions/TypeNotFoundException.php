<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 29.05.18
 * Time: 11:26
 */

namespace App\Exceptions;

/**
 * Class TypeNotFoundException
 * @package App\Exceptions
 */
class TypeNotFoundException extends AbstractBaseException
{
    /**
     * ParentDataNotFoundException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 404)
    {
        parent::__construct($message, $code);
    }
}