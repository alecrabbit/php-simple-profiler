<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:23
 */

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

interface OldFormatterInterface
{
    /**
     * @return string
     */
    public function process(): string;
}
