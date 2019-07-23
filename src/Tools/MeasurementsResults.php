<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use MathPHP\Statistics\Average;
use MathPHP\Statistics\RandomVariable;

class MeasurementsResults
{
    protected const REJECTION_THRESHOLD = 10;

    public static function createResult($measurements): BenchmarkResult
    {
        self::refine($measurements, $numberOfRejections);
        $mean = Average::mean($measurements);
        $standardErrorOfTheMean = RandomVariable::standardErrorOfTheMean($measurements);
        $numberOfMeasurements = count($measurements);
        $tValue = TDistribution::tValue($numberOfMeasurements);

        return
            new BenchmarkResult(
                $mean,
                $standardErrorOfTheMean * $tValue,
                $numberOfMeasurements,
                $numberOfRejections
            );
    }

    protected static function refine(array &$measurements, ?int &$rejections): void
    {
        self::removeMax($measurements);
        $rejections = $rejections ?? 0;
        $meanCorr = Average::mean($measurements) * (1 + self::REJECTION_THRESHOLD / 100);

        foreach ($measurements as $key => $value) {
            if ($value > $meanCorr) {
                unset($measurements[$key]);
                $rejections++;
            }
        }
    }

    protected static function removeMax(array &$measurements): void
    {
        $max = max($measurements);
        unset($measurements[array_search($max, $measurements, true)]);
    }
}