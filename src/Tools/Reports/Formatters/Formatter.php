<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use function AlecRabbit\typeOf;

abstract class Formatter implements FormatterInterface, Strings
{
    /** {@inheritdoc} */
    abstract public function process(Formattable $formattable): string;

    /**
     * @param string $expected
     * @param Formattable $formattable
     * @throws \RuntimeException
     */
    protected function wrongFormattableType(string $expected, Formattable $formattable): void
    {
        throw new \RuntimeException(
            'Instance of [' . $expected . '] expected, [' . typeOf($formattable) . '] given.'
        );
    }
}
