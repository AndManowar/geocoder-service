<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 07.06.18
 * Time: 9:49
 */

namespace App\Exceptions;

/**
 * Class InvalidMapDataException
 * @package App\Exceptions
 */
class InvalidMappingDataException extends AbstractBaseException
{
    /**
     * InvalidMapDataException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message, $code);
    }
}