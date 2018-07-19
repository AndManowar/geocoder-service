<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 02.07.18
 * Time: 12:43
 */

namespace App\Validators;

use App\Exceptions\ObjectNotFoundException;
use App\Models\DB\MetroLineModel;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

/**
 * Валидатор метро
 *
 * Class MetroValidator
 * @package App\Validators
 */
class MetroValidator extends AbstractValidator
{
    /**
     * Object data validation
     *
     * @param array $data
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function validateData(array $data): bool
    {
        $this->add(['metro_line_id', 'station_name', 'keywords'], new PresenceOf());
        $this->add('metro_line_id', new Digit());
        $this->add('station_name', new StringLength(['max' => 60]));

        $this->setErrors($this->validate($data));

        if (isset($data['metro_line_id'])) {
            $this->checkMetroLine($data['metro_line_id']);
        }

        return empty($this->errors);
    }

    /**
     * Проверка существования линии метро с присланным ИД
     *
     * @param int $metroLineId
     * @throws ObjectNotFoundException
     */
    private function checkMetroLine(int $metroLineId): void
    {
        $metroLine = MetroLineModel::query()
            ->where('id = :id:', ['id' => $metroLineId])
            ->execute()
            ->getFirst();

        if (!$metroLine) {
            throw new ObjectNotFoundException("Metro Line With id {$metroLineId} Not Found");
        }
    }
}