<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 28.05.18
 * Time: 11:16
 */

namespace App\Exceptions;

/**
 * Class ParentDataNotFoundException
 * @package App\Exceptions
 */
class ParentDataNotFoundException extends AbstractBaseException
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