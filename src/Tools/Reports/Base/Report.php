<?php
/**
 * User: alec
 * Date: 29.11.18
 * Time: 20:57
 */

namespace AlecRabbit\Tools\Reports\Base;

use function AlecRabbit\brackets;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use function AlecRabbit\typeOf;

class Report implements ReportInterface
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return
            brackets(typeOf($this) . '::' . __FUNCTION__) . ' Not implemented!';
    }
}
