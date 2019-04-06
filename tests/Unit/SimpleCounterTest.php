<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Tools\SimpleCounter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class SimpleCounterTest extends TestCase
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
        $c = new SimpleCounter(...$params);
        [$name, $value, $step, $initial] = $expected;
        $this->assertEquals($name, $c->getName());
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals($step, $c->getStep());
        $this->assertEquals($initial, $c->getInitialValue());
        $this->assertEquals(0, $c->getBumped());
        $value += $step;
        $c->bump();
        $this->assertEquals($value, $c->getValue());
        $this->assertEquals(1, $c->getBumped());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function simpleCounterSetStep(): void
    {
        $c = new SimpleCounter();
        $c->setStep(2);
        $c->bump(2);
        $this->assertEquals(4, $c->getValue());
        $this->expectException(\RuntimeException::class);
        $c->setStep(2);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function simpleCounterSetInitialValue(): void
    {
        $c = new SimpleCounter();
        $c->setInitialValue(2);
        $c->bump(2);
        $this->assertEquals(4, $c->getValue());
        $this->expectException(\RuntimeException::class);
        $c->setInitialValue(2);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function simpleCounterSetStep0(): void
    {
        $c = new SimpleCounter();
        $this->expectException(\RuntimeException::class);
        $c->setStep(0);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function simpleCounterBump0(): void
    {
        $c = new SimpleCounter();
        $this->expectException(\RuntimeException::class);
        $c->bump(0);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function simpleCounterBumpNegative(): void
    {
        $c = new SimpleCounter();
        $this->expectException(\RuntimeException::class);
        $c->bump(-1);
    }

    /**
     * @return array
     */
    public function simpleCounterInstanceDataProvider(): array
    {
        $pop = 'pop';
        $name = 'name';
        return [
            // [$name, $value, $step, $initial], [$name, $step, $initial]
            [[DEFAULT_NAME, 0, 1, 0], []],
            [[DEFAULT_NAME, 0, 1, 0], [null]],
            [[DEFAULT_NAME, 1, 1, 1], [null, 1, 1]],
            [[$name, 0, 1, 0], [$name]],
            [[$pop, 0, 1, 0], [$pop]],
            [[$pop, 10, 1, 10], [$pop, 1, 10]],
        ];
    }

    public function simpleCounterForExceptions()
    {
        return [
            [],
        ];
    }
}
