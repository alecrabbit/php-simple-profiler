<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters\Contracts;

use AlecRabbit\Tools\Formattable;

interface FormatterInterface
{
    /**
     * @param Formattable $formattable
     * @return string
     */
    public function process(Formattable $formattable): string;
}
