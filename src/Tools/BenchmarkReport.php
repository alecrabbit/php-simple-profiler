<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Tools\Internal\BenchmarkRelative;
use function AlecRabbit\typeOf;

class BenchmarkReport
{
    /** @var bool */
    protected $showReturns = false;
    /** @var null|BenchmarkFunction[] */
    protected $functions;

    public function withReturns(): self
    {
        $this->showReturns = true;
        return $this;
    }

    public function __toString(): string
    {
        $functions = $this->updateFunctions($this->functions);

        $str = '';
        /**
         * @var BenchmarkFunction $f
         */
        foreach ($functions as $name => $f) {
            $benchmarkRelative = $f->getRelative();
            if ($benchmarkRelative instanceof BenchmarkRelative) {
//                dump($benchmarkRelative);
                $str .=
                    sprintf(
                        '%s. %s %s [%s]',
                        $benchmarkRelative->getRank(),
                        $f->getHumanReadableName(),
                        (string)$benchmarkRelative->getBenchmarkResult(),
                        Pretty::percent($benchmarkRelative->getRelative())
                    ) . PHP_EOL;
            }
        }
        // Form report
        return $str;
    }

    /**
     * @param BenchmarkFunction[] $functions
     * @return array
     */
    private function updateFunctions(array $functions): array
    {
//        dump($functions);
        $averages = $this->computeAverages();
        $relatives = $this->computeRelatives($averages);
        $updatedFunctions = [];
        if (!empty($relatives)) {
            $rank = 0;
            foreach ($relatives as $name => $relative) {
                /** @var BenchmarkFunction $function */
                $function = $functions[$name] ?? null;
                $average = $averages[$name] ?? null;
//                dump($name, typeOf($function), $average);
                if (null !== $function && null !== $average) {
                    $function->setBenchmarkRelative(
                        new BenchmarkRelative(++$rank, (float)$relative - 1, $function->getResult())
                    );
                }
                unset($functions[$name]);
                $updatedFunctions[$name] = $function;
            }
        }
        foreach ($functions as $name => $function) {
            $updatedFunctions[$name] = $function;
        }
        return $updatedFunctions;
    }

    /**
     * @return array
     */
    protected function computeAverages(): array
    {
        $averages = [];
        /** @var BenchmarkFunction $f */
        foreach ($this->functions as $f) {
            $averages[$f->getIndexedName()] = $f->getResult()->getMean();
        }
        return $averages;
    }

    private function computeRelatives(array $averages): array
    {
        $rel = [];
        if (!empty($averages)) {
            $min = min($averages);

            foreach ($averages as $name => $average) {
                $rel[$name] = $average / $min;
            }
            asort($rel);
        }
        return $rel;
    }

    public function setFunctions(array $functions): self
    {
        $this->functions = [];
        /** @var BenchmarkFunction $f */
        foreach ($functions as $f) {
            $this->functions[$f->getIndexedName()] = $f;
        }

        return $this;
    }
}
