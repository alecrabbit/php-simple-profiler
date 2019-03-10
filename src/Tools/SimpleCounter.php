<?php declare(strict_types=1);

namespace AlecRabbit\Tools;

use AlecRabbit\Tools\Reports\SimpleCounterReport;

class SimpleCounter extends AbstractCounter
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function __construct(?string $name = null, ?int $step = null, int $initialValue = 0)
    {
        parent::__construct($name, $step, $initialValue);
        $this->buildReport();
    }

    /**
     * @throws \Exception
     */
    protected function buildReport(): void
    {
        $this->report = (new SimpleCounterReport())->buildOn($this);
    }
}
