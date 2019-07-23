<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use MathPHP\Statistics\Average;

class MeasurementsResults
{
    protected const REJECT_COEFFICIENT = 1.1;

    protected function removeMax(array &$measurements): void
    {
        $max = max($measurements);
        unset($measurements[array_search($max, $measurements, true)]);
    }

    public function refine(array &$measurements, ?int &$rejections): void
    {
        $this->removeMax($measurements);
        $rejections = $rejections ?? 0;
        $meanCorr = Average::mean($measurements) * self::REJECT_COEFFICIENT;

        foreach ($measurements as $key => $value) {
            if ($value > $meanCorr) {
                unset($measurements[$key]);
                $rejections++;
            }
        }
    }


}