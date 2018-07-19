<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 06.06.18
 * Time: 13:18
 */

namespace App\Validators;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

/**
 * Class FolkDistrictMapValidator
 * @package App\Validators
 */
class FolkDistrictMappingValidator extends AbstractValidator
{
    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        $this->add(['folk_district_glob_id', 'formal_district_glob_id'], new PresenceOf());
        $this->add(['folk_district_glob_id', 'formal_district_glob_id'], new StringLength(['max' => 46]));

        $this->setErrors($this->validate($data));

        return empty($this->errors);
    }
}