<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 12:02
 */

namespace App\Validators;

use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class DirectionValidator
 * @package App\Validators
 */
class DirectionValidator extends AbstractValidator
{

    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        $this->add(['name', 'region_id'], new PresenceOf());
        $this->add('region_id', new Digit());

        $this->setErrors($this->validate($data));

        return empty($this->errors);
    }
}