<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports;

use AlecRabbit\Tools\AbstractCounter;
use AlecRabbit\Tools\Contracts\CounterInterface;
use AlecRabbit\Tools\ExtendedCounter;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Core\Report;
use AlecRabbit\Tools\Reports\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Tools\SimpleCounter;
use AlecRabbit\Tools\Traits\ExtendedCounterFields;
use AlecRabbit\Tools\Traits\SimpleCounterFields;
use function AlecRabbit\typeOf;

class SimpleCounterReport extends AbstractCounterReport
{
}
