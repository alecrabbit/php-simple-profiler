<?php

namespace Tests\Unit;

use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;

class UnimplementedReport implements OldReportInterface
{
    public function __toString()
    {
        return '';
    }
}
