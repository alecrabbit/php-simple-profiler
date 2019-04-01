<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\ConsoleColour\Exception\InvalidStyleException;
use AlecRabbit\ConsoleColour\Themes;
use AlecRabbit\Tools\Internal\BenchmarkFunction;
use function AlecRabbit\str_wrap;
use function AlecRabbit\typeOf;

class BenchmarkFunctionSymfonyFormatter extends BenchmarkFunctionFormatter
{
    /** @var Themes */
    protected $theme;
    /** @var float */
    protected $yellowThreshold;
    /** @var float */
    protected $redThreshold;

    /**
     * BenchmarkFunctionSymfonyFormatter constructor.
     * @throws InvalidStyleException
     */
    public function __construct()
    {
        $this->theme = new Themes(true);
        $this->yellowThreshold = 0.05;
        $this->redThreshold = 0.9;
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
     * @param BenchmarkFunction $function
     *
     * @return string
     */
    protected function preformatFunction(
        BenchmarkFunction $function
    ): string {
        $argumentsTypes = $this->extractArgumentsTypes($function->getArgs());
        if ($br = $function->getBenchmarkRelative()) {
            $rank = $br->getRank();
            return
                sprintf(
                    '%s. %s(%s) %s %s',
                    (string)$rank,
                    $this->prepAverage($rank, $br->getAverage()),
                    $this->relativePercent($br->getRelative()),
                    $this->prepName($function, $argumentsTypes),
                    $this->theme->yellow($function->comment())
                );
        }
    }

    /**
     * @param int $rank
     * @param float $average
     * @return string
     */
    protected function prepAverage(int $rank, float $average): string
    {
        return
            $rank === 1 ?
                $this->averageFirst($average) :
                $this->average($average);
    }

    protected function averageFirst(float $average): string
    {
//        $this->theme->underline($avg);
        $str = Pretty::time($average);
        $len = strlen($str);
        $proto = str_repeat('X', $len);
        $res = str_pad(
            $proto,
            8,
            ' ',
            STR_PAD_LEFT
        );
        return
            str_replace($proto, $this->theme->underlinedBold($str), $res);
    }

    /**
     * @param float $relative
     * @param string $prefix
     * @return string
     */
    protected function relativePercent(float $relative, string $prefix = '+'): string
    {
        $color = 'green';

        if ($relative >= $this->yellowThreshold) {
            $color = 'yellow';
        }
        if ($relative > $this->redThreshold) {
            $color = 'red';
        }
        return
            $this->theme->$color(
                parent::relativePercent($relative, $prefix)
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
