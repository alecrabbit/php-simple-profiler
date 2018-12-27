<?php
/**
 * User: alec
 * Date: 10.12.18
 * Time: 14:25
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Internal\Theme;
use AlecRabbit\Tools\Reports\Contracts\ReportInterface;
use AlecRabbit\Tools\Reports\Factory;
use AlecRabbit\Tools\Reports\Formatters\Contracts\ReportFormatter;

abstract class Formatter implements ReportFormatter
{
    /** @var ReportInterface */
    protected $report;
    /** @var Theme */
    protected $theme;

    /**
     * Formatter constructor.
     * @param ReportInterface $report
     * @throws \JakubOnderka\PhpConsoleColor\InvalidStyleException
     */
    public function __construct(ReportInterface $report)
    {
        $this->report = $report;
        $this->theme = Factory::getThemeObject();
        $this->setStyles();
    }

    /** {@inheritdoc} */
    abstract public function setStyles(): void;

    /** {@inheritdoc} */
    abstract public function getString(): string;
}
