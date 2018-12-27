<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Base;

use AlecRabbit\Tools\Reports\Contracts\ReportableInterface;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;

abstract class Report implements ReportInterface
{

    protected $formatter;

    public function __construct()
    {
        $this->formatter = Factory::makeFormatter($this);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return
            $this->formatter->getString();
    }
}
