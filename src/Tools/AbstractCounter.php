<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Contracts\CounterInterface;
use AlecRabbit\Tools\Contracts\Strings;
use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\HasReport;

abstract class AbstractCounter implements CounterInterface, ReportableInterface, Strings
{
    use HasReport;

    protected const DEFAULT_STEP = 1;
}