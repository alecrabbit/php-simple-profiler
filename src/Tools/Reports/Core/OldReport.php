<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Core;

use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatter;

abstract class OldReport implements OldReportInterface
{
    /** @var OldFormatter */
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
            $this->formatter->process();
    }
}
