<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 07.06.18
 * Time: 9:46
 */

namespace App\Exceptions;

/**
 * Class FolkDistrictNotFoundException
 * @package App\Exceptions
 */
class FolkDistrictNotFoundException extends AbstractBaseException
{
    /**
     * FolkDistrictNotFoundException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 404)
    {
        parent::__construct($message, $code);
    }
}