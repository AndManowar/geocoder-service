<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 24.05.18
 * Time: 9:16
 */

namespace App\Validators;

use App\Exceptions\InvalidLevelValueException;
use App\Models\DB\ObjectInfoModel;
use App\Models\DB\ObjectModel;
use App\Service\SearchService;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

/**
 * Class ObjectValidator
 * @package App\Validators
 */
class ObjectValidator extends AbstractValidator
{
    /**
     * @var ObjectInfoModel
     */
    private $objectInfo;

    /**
     * Set model for unique validation
     *
     * @param ObjectInfoModel $objectInfo
     * @return void
     */
    public function setModel(ObjectInfoModel $objectInfo): void
    {
        $this->objectInfo = $objectInfo;
    }

    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     */
    public function validateData(array $data): bool
    {
        $this->add(['name'], new PresenceOf());

        $this->setErrors($this->validate($data));

        return empty($this->errors);
    }

    /**
     * Object validation after filling all attributes
     *
     * @param ObjectModel $objectModel
     * @return bool
     * @throws InvalidLevelValueException
     */
    public function validateObjectAfterBuilding(ObjectModel $objectModel): bool
    {
        $this->add([
            'name',
            'full_name',
            'type_id',
            'glob_id',
            'parent_data',
            'end_date',
            'parent_glob_id',
            'level',
            'region_id',
            'status'
        ], new PresenceOf());

        $this->add(['type_id', 'level', 'status'], new Digit());

        $this->add('level', new InclusionIn([
            'domain' => array_unique(array_merge(array_keys(SearchService::$levelsSteps), SearchService::$levelsSteps))
        ]));
        $this->add('status', new InclusionIn([
            'domain' => [ObjectModel::STATUS_ACTIVE, ObjectModel::STATUS_DELETED]
        ]));

        $this->add(['glob_id', 'parent_glob_id', 'fias_entry_id'], new StringLength(['max' => 46]));

        $this->getAdditionalValidationRules($objectModel->level);

        $this->setErrors($this->validate(array_merge($objectModel->toArray(), $objectModel->info->toArray())));

        return empty($this->errors);
    }

    /**
     * Get additional validation rules by level
     *
     * @param int $level
     * @throws InvalidLevelValueException
     */
    private function getAdditionalValidationRules(int $level): void
    {
        switch ($level) {
            case ObjectModel::LEVEL_COUNTY:
            case ObjectModel::LEVEL_AREA:
                $this->levelThreeRules();
                break;
            case ObjectModel::LEVEL_CITY:
                $this->levelFourRules();
                break;
            case ObjectModel::LEVEL_CITY_DISTRICT:
                $this->levelFiveRules();
                break;
            case ObjectModel::LEVEL_PLACE:
                $this->levelSixRules();
                break;
            case ObjectModel::LEVEL_STREET:
                $this->levelSevenRules();
                break;
            case ObjectModel::LEVEL_ADDITIONAL_TERRITORY:
            case ObjectModel::LEVEL_ADDITIONAL_TERRITORY_SUBJECT:
                $this->lastLevelsRules();
                break;
            default:
                throw new InvalidLevelValueException("Level {$level} Is Invalid");
        }
    }

    /**
     * Check fias id uniqueness
     *
     * @param string $fiasId
     * @return bool
     */
    public function validateUniqueFias(string $fiasId): bool
    {
        return empty(ObjectInfoModel::query()->where("fias_entry_id='{$fiasId}'")->execute()->getFirst());
    }

    /**
     * Additional rules for level = 3
     *
     * @return void
     */
    private function levelThreeRules(): void
    {
        $this->add('area_id', new PresenceOf());
        $this->add('area_id', new Digit());
    }

    /**
     * Additional rules for level = 4
     *
     * @return void
     */
    private function levelFourRules(): void
    {
        $this->levelThreeRules();

        $this->add('city_id', new PresenceOf());
        $this->add('city_id', new Digit());
    }

    /**
     * Additional rules for level = 5
     *
     * @return void
     */
    private function levelFiveRules(): void
    {
        $this->levelFourRules();

        $this->add('city_district_id', new PresenceOf());
        $this->add('city_district_id', new Digit());
    }

    /**
     * Additional rules for level = 6
     *
     * @return void
     */
    private function levelSixRules(): void
    {
        $this->levelFiveRules();

        $this->add('place_id', new PresenceOf());
        $this->add('place_id', new Digit());
    }


    /**
     * Additional rules for level = 7
     *
     * @return void
     */
    private function levelSevenRules(): void
    {
        $this->levelSixRules();

        $this->add('street_id', new PresenceOf());
        $this->add('street_id', new Digit());
    }

    /**
     * Additional rules for level = 90 and 91
     *
     * @return void
     */
    private function lastLevelsRules(): void
    {
        $this->levelSevenRules();

        $this->add('external_id', new PresenceOf());
        $this->add('external_id', new Digit());
    }

}