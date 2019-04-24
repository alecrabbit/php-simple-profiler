<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters\Contracts;

use AlecRabbit\Accessories\Contracts\FormatterInterface as BaseFormatterInterface;
use AlecRabbit\Tools\Formattable;

interface FormatterInterface extends BaseFormatterInterface
{
    /**
     * @param Formattable $formattable
     * @return string
     */
    public function process(Formattable $formattable): string;
}
