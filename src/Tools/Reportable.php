<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Traits\HasReport;

abstract class Reportable implements ReportableInterface
{
    use  HasReport;
}