<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Base;

use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;
use function AlecRabbit\brackets;
use function AlecRabbit\typeOf;

class Report implements ReportInterface
{

    protected $formatter;

    public function __construct(ReportInterface $report)
    {
        $this->formatter = Factory::makeFormatter($report);
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return
            brackets(typeOf($this) . '::' . __FUNCTION__) . ' Not implemented!';
    }
}
