<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Contracts\StringConstants;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatterInterface;

abstract class OldReportFormatterInterface implements OldFormatterInterface, StringConstants
{
    /** {@inheritdoc} */
    abstract public function process(): string;
}
