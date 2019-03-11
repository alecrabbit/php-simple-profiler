<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\ExtendedCounter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class ExtendedCounterTest extends TestCase
{
    /**
     * @test
     * @dataProvider simpleCounterInstanceDataProvider
     * @param array $expected
     * @param array $params
     * @throws \Exception
     */
    public function simpleCounterInstance(array $expected, array $params): void
    {
        $c = new ExtendedCounter(...$params);
        [$name, $value, $step, $initial, $diff] = $expected;
        $this->assertEquals($name, $c->getName());
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals($step, $c->getStep());
        $this->assertEquals($initial, $c->getInitialValue());
        $this->assertEquals($diff, $c->getDiff());
        $this->assertEquals(0, $c->getBumped());
        $value += $step;
        $c->bump();
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals(1, $c->getBumped());
        $value -= $step;
        $c->bumpBack();
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals(1, $c->getBumped());
        $this->assertEquals(1, $c->getBumpedBack());
        $this->assertEquals($initial, $c->getMin());
        $this->assertEquals($initial + $step, $c->getMax());
        $this->assertEquals($step * 2, $c->getPath());
        $this->assertEquals($initial + $step * 2, $c->getLength());
        $c->bumpBack(4);
        $this->assertEquals($initial- $step * 4, $c->getMin());

    }

    /**
     * @return array
     */
    public function simpleCounterInstanceDataProvider(): array
    {
        $pop = 'pop';
        $name = 'name';
        return [
            // [$name, $value, $step, $initial, $diff], [$name, $step, $initial]
            [[DEFAULT_NAME, 0, 1, 0, 0], []],
            [[DEFAULT_NAME, 0, 1, 0, 0], [null]],
            [[DEFAULT_NAME, 1, 1, 1, 0], [null, 1, 1]],
            [[DEFAULT_NAME, 1, 2, 1, 0], [null, 2, 1]],
            [[$name, 0, 1, 0, 0], [$name]],
            [[$pop, 0, 1, 0, 0], [$pop]],
            [[$pop, 10, 1, 10, 0], [$pop, 1, 10]],
        ];
    }
}
