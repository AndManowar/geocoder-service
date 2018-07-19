<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 21.05.18
 * Time: 10:36
 */

namespace App\Exceptions;

/**
 * Class InvalidTypeDescException
 * @package App\Service\Geocoder\Exceptions
 */
class InvalidTypeDescException extends AbstractBaseException
{
    /**
     * InvalidTypeDescException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}