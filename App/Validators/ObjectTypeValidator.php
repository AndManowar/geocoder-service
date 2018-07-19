<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 24.05.18
 * Time: 9:16
 */

namespace App\Validators;

use App\Service\SearchService;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class TypeDescValidator
 * @package App\Validators
 */
class ObjectTypeValidator extends AbstractValidator
{
    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        $this->add('name', new PresenceOf());
        $this->add('level', new PresenceOf());
        $this->add('level', new Digit());
        $this->add('level', new InclusionIn([
            'domain' => array_unique(array_merge(array_keys(SearchService::$levelsSteps), SearchService::$levelsSteps))
        ]));

        $this->setErrors($this->validate($data));

        return empty($this->errors);
    }
}