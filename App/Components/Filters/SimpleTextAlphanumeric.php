<?php
namespace App\Components\Filters;

use Phalcon\Filter\UserFilterInterface;

/**
 * Class SimpleTextAlphanumeric
 * Свой фильтр для Phalcon\Filter
 * - Получение строки соджержащей только  буквы/цифры/пробелы/подчеркивания
 * - Ограничение до длины в 30 символов
 * - используеться для параметров FullText поиска
 *
 * @package App\Components\Filters
 * @author lex gudz sd1328@gmail.com
 */
class SimpleTextAlphanumeric implements UserFilterInterface
{
    const DEFAULT_MAX_STRING_LEN = 30;
    const DISALLOW_CHARS_PATTERN = '/[^\w\s]+/u';

    protected $maxLen;

    public function __construct(int $maxLen = self::DEFAULT_MAX_STRING_LEN )
    {
        $this->maxLen = $maxLen;
    }

    public function filter($value)
    {
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        $value = preg_replace(self::DISALLOW_CHARS_PATTERN, '', $value);
        $value = mb_strimwidth($value, 0, $this->maxLen);
        return $value;
    }

}