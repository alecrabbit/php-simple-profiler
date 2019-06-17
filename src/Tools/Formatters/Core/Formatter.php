<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters\Core;

use AlecRabbit\Formatters\Core\AbstractFormatter;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Formatters\Contracts\FormatterInterface;
use function AlecRabbit\typeOf;

abstract class Formatter extends AbstractFormatter implements FormatterInterface, Strings
{

    /** {@inheritdoc} */
    abstract public function format(Formattable $formattable): string;

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
