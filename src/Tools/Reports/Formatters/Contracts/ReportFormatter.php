<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:23
 */

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

interface ReportFormatter
{
    /**
     * @throws \JakubOnderka\PhpConsoleColor\InvalidStyleException
     */
    public function setStyles(): void;

    public function getString(): string;
}
