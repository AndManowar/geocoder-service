<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 17:18
 */

namespace App\Validators;

use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

/**
 * Валидатор линий метро
 *
 * Class MetroLineValidator
 * @package App\Validators
 */
class MetroLineValidator extends AbstractValidator
{
    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        $this->add(['region_id', 'name', 'color'], new PresenceOf());
        $this->add(['region_id'], new Digit());
        $this->add(['name', 'color'], new StringLength(['max' => 60]));

        $this->setErrors($this->validate($data));

        return empty($this->errors);
    }
}