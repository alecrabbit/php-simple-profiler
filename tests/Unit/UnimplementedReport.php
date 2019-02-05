<?php

namespace Tests\Unit;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;

class UnimplementedReport implements ReportInterface
{
    public function __toString()
    {
        return '';
    }

}