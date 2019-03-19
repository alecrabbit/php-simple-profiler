<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Formatters\Colour\Theme;

class BenchmarkFunctionSymfonyFormatter extends BenchmarkFunctionFormatter
{
    /** @var Theme */
    protected $theme;

    /**
     * BenchmarkFunctionSymfonyFormatter constructor.
     * @throws InvalidStyleException
     */
    public function __construct()
    {
        $this->theme = new Theme(true);
    }

    /**
     * @param BenchmarkRelative $br
     * @param BenchmarkFunction $function
     * @param array $argumentsTypes
     *
     * @return string
     */
    protected function preformatFunction(
        BenchmarkRelative $br,
        BenchmarkFunction $function,
        array $argumentsTypes
    ): string {
        return
            sprintf(
                '%s. %s (%s) %s(%s) %s',
                (string)$br->getRank(),
                $this->average($br->getAverage()),
                $this->relativePercent($br->getRelative()),
                $function->humanReadableName(),
                $this->theme->dark(implode(', ', $argumentsTypes)),
                $this->theme->yellow($function->comment())
            );
    }

    /**
     * @param float $relative
     * @return string
     */
    protected function relativePercent(float $relative): string
    {
        $color = 'green';
        if ($relative > 1) {
            $color = 'red';
        }
        if ($relative >= 0.03) {
            $color = 'yellow';
        }
        return
            $this->theme->$color(
                parent::relativePercent($relative)
            );
    }

}
