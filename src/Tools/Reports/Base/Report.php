<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Base;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\Contracts\ReportFormatter;

abstract class Report implements ReportInterface
{
    /** @var ReportFormatter */
    protected $formatter;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $this->formatter = Factory::makeFormatter($this);
    }

    /** {@inheritdoc} */
    public function __toString(): string
    {
        return
            $this->formatter->getString();
    }
}
