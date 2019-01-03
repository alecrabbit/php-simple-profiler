<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:23
 */

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

use AlecRabbit\Exception\InvalidStyleException;

interface ReportFormatter
{
    /**
     * @throws InvalidStyleException
     */
    public function setStyles(): void;

    /**
     * @return string
     */
    public function getString(): string;
}
