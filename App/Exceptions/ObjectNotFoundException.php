<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 21.05.18
 * Time: 10:29
 */

namespace App\Exceptions;

/**
 * Class ObjectNotFoundException
 * @package App\Service\Geocoder\Exceptions
 */
class ObjectNotFoundException extends AbstractBaseException
{
    /**
     * ObjectNotFoundException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 404)
    {
        parent::__construct($message, $code);
    }
}