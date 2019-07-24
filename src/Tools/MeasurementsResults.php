<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use MathPHP\Statistics\Average;
use MathPHP\Statistics\RandomVariable;

class MeasurementsResults
{
    protected const REJECTION_THRESHOLD = 10;

    public static function createResult(array $measurements): BenchmarkResult
    {
        $measurements = self::convertDataType($measurements);
        self::refine($measurements, $numberOfRejections);
        $numberOfMeasurements = count($measurements);
        $mean = Average::mean($measurements);
        $standardErrorOfTheMean = RandomVariable::standardErrorOfTheMean($measurements);
        $tValue = TDistribution::tValue($numberOfMeasurements);

        return
            new BenchmarkResult(
                $mean,
                $standardErrorOfTheMean * $tValue,
                $numberOfMeasurements,
                $numberOfRejections
            );
    }

    protected static function convertDataType(array $measurements): array
    {
        if ($measurements[0] instanceof BenchmarkResult) {
            $m = [];
            /** @var BenchmarkResult $r */
            foreach ($measurements as $r) {
                $m[] = $r->getMean();
            }
            $measurements = $m;
        }
        return $measurements;
    }

    protected static function refine(array &$measurements, ?int &$rejections): void
    {
        self::removeMax($measurements);
        $rejections = $rejections ?? 0;
        $meanThreshold = Average::mean($measurements) * self::rejectionCoefficient();

        foreach ($measurements as $key => $value) {
            if ($value > $meanThreshold) {
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

    /**
     * @return float|int
     */
    protected static function rejectionCoefficient()
    {
        return 1 + self::REJECTION_THRESHOLD / 100;
    }
}