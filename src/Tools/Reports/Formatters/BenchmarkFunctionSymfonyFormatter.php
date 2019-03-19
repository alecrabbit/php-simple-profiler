<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

use AlecRabbit\Tools\Formattable;
use AlecRabbit\Tools\Internal\BenchmarkFunction;

class BenchmarkFunctionSymfonyFormatter extends BenchmarkFunctionFormatter
{
    /** {@inheritdoc} */
    public function process(Formattable $function): string
    {
        if ($function instanceof BenchmarkFunction) {
            return
                $this->formatBenchmarkRelative($function) .
                (empty($exception = $this->formatException($function)) ?
                    PHP_EOL :
                    static::EXCEPTIONS . PHP_EOL . $exception);
        }
        $this->wrongFormattableType(BenchmarkFunction::class, $function);
        // @codeCoverageIgnoreStart
        return ''; // never executes
        // @codeCoverageIgnoreEnd
    }

}
