<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Exception\InvalidStyleException;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use AlecRabbit\Tools\Reports\Formatters\Colour\Theme;
use function AlecRabbit\str_wrap;
use function AlecRabbit\typeOf;

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

    /** {@inheritdoc} */
    public function returnToString($executionReturn): string
    {
        $type = typeOf($executionReturn);
        $str = static::getExporter()->export($executionReturn);
        return
            $this->theme->dark(
                'array' === $type ?
                    $str :
                    sprintf(
                        '%s(%s)',
                        $type,
                        $str
                    )
            );
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
        $rank = $br->getRank();
        $average = $this->average($br->getAverage());
        return
            sprintf(
                '%s. %s (%s) %s %s',
                (string)$rank,
                $rank !== 1 ? $average : $this->theme->underline($average),
                $this->relativePercent($br->getRelative()),
                $this->prepName($function, $argumentsTypes),
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

    /**
     * @param BenchmarkFunction $function
     * @param array $argumentsTypes
     * @return mixed
     */
    protected function prepName(BenchmarkFunction $function, array $argumentsTypes)
    {
        return
            sprintf(
                '%s%s%s%s',
                $this->theme->italic($function->humanReadableName()),
                $this->theme->italic('('),
                $this->theme->darkItalic(implode(', ', $argumentsTypes)),
                $this->theme->italic(')')
            );
    }

    /**
     * @param BenchmarkFunction $function
     * @return string
     */
    protected function formatException(BenchmarkFunction $function): string
    {

        if ($e = $function->getException()) {
            $argumentsTypes = $this->extractArgumentsTypes($function->getArgs());

            return
                sprintf(
                    '%s %s%s[%s%s%s]%s',
                    $this->prepName($function, $argumentsTypes),
                    $this->theme->yellow($function->comment()),
                    ' ',
                    $this->theme->error(str_wrap(typeOf($e), ' ')),
                    ' : ',
                    $this->theme->dark($e->getMessage()),
                    PHP_EOL
                );
        }

        return '';
    }
}
