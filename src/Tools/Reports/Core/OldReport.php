<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Core;

use AlecRabbit\Tools\Reports\Contracts\OldReportInterface;
use AlecRabbit\Tools\Reports\OldFactory;
use AlecRabbit\Tools\Reports\Formatters\Contracts\OldFormatterInterface;

abstract class OldReport implements OldReportInterface
{
    /** @var OldFormatterInterface */
    protected $formatter;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $this->formatter = OldFactory::makeFormatter($this);
    }

    /** {@inheritdoc} */
    public function __toString(): string
    {
        return
            $this->formatter->process();
    }
}
