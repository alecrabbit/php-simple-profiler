<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 2:26
 */

namespace AlecRabbit\Tools\Reports\Contracts;

interface ReportInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param ReportableInterface $reportable
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $reportable): void;
}
