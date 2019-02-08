<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Tests\Unit;

use AlecRabbit\Tools\Counter;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class CounterTest extends TestCase
{
    protected const NAME = 'name';

    /** @test */
    public function counterDefaultCreation(): void
    {
        $c = new Counter();
        $this->assertInstanceOf(Counter::class, $c);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $this->assertEquals(0, $c->getValue());
        $this->assertEquals(0, $c->getPath());
        $this->assertEquals(0, $c->getLength());
        $this->assertEquals(0, $c->getInitialValue());
        $this->assertEquals(1, $c->getStep());
        $this->assertEquals(0, $c->getDiff());

        $c = new Counter(self::NAME);
        $this->assertEquals(self::NAME, $c->getName());
        $this->assertEquals(0, $c->getValue());
        $this->assertEquals(0, $c->getPath());
        $this->assertEquals(0, $c->getLength());
        $this->assertEquals(0, $c->getInitialValue());
        $this->assertEquals(1, $c->getStep());
        $this->assertEquals(0, $c->getDiff());
    }

    /** @test */
    public function counterCreationWithParams(): void
    {
        $c = new Counter(self::NAME, 2, 2);
        $this->assertEquals(self::NAME, $c->getName());
        $this->assertEquals(2, $c->getValue());
        $this->assertEquals(0, $c->getPath());
        $this->assertEquals(2, $c->getLength());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(0, $c->getDiff());

        $c = new Counter(null, 2, 2);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $this->assertEquals(2, $c->getValue());
        $this->assertEquals(0, $c->getPath());
        $this->assertEquals(2, $c->getLength());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(0, $c->getDiff());

        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->assertEquals(2, $c->getPath());
        $this->assertEquals(4, $c->getLength());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(2, $c->getDiff());

        $c->bumpBack();
        $this->assertEquals(2, $c->getValue());
        $this->assertEquals(4, $c->getPath());
        $this->assertEquals(6, $c->getLength());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(0, $c->getDiff());
    }

    /** @test */
    public function counterDefaultCreationWithMethods(): void
    {
        $c = new Counter();
        $c->setStep(2)->setInitialValue(2);
        $this->assertInstanceOf(Counter::class, $c);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $this->assertEquals(2, $c->getValue());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(0, $c->getDiff());

        $c = new Counter(self::NAME);
        $c->setStep(2)->setInitialValue(2);
        $this->assertEquals(self::NAME, $c->getName());
        $this->assertEquals(2, $c->getValue());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(0, $c->getDiff());
        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->assertEquals(2, $c->getInitialValue());
        $this->assertEquals(2, $c->getStep());
        $this->assertEquals(2, $c->getDiff());
    }

    /** @test */
    public function counterBump(): void
    {
        $c = new Counter();
        $this->assertEquals(DEFAULT_NAME, $c->getName());

        $c->bump();
        $this->assertEquals(1, $c->getValue());
        $this->assertEquals(1, $c->getPath());
        $this->assertEquals(1, $c->getLength());

        $c->bump(2);
        $this->assertEquals(3, $c->getValue());
        $this->assertEquals(3, $c->getPath());
        $this->assertEquals(3, $c->getLength());

        $c->bump(2);
        $this->assertEquals(5, $c->getValue());
        $this->assertEquals(5, $c->getPath());
        $this->assertEquals(5, $c->getLength());

        $c->bump(2);
        $this->assertEquals(7, $c->getValue());
        $this->assertEquals(7, $c->getPath());
        $this->assertEquals(7, $c->getLength());

        $c->bumpBack(2);
        $this->assertEquals(5, $c->getValue());
        $this->assertEquals(9, $c->getPath());
        $this->assertEquals(9, $c->getLength());
        $this->assertEquals(0, $c->getInitialValue());
        $this->assertEquals(5, $c->getDiff());
        $this->assertEquals(4, $c->getBumpedForward());
        $this->assertEquals(1, $c->getBumpedBack());

        $c->bump(2, false);
        $this->assertEquals(3, $c->getValue());
        $this->assertEquals(11, $c->getPath());
        $this->assertEquals(11, $c->getLength());
        $this->assertEquals(3, $c->getDiff());
        $this->assertEquals(4, $c->getBumpedForward());
        $this->assertEquals(2, $c->getBumpedBack());
    }

    /** @test */
    public function counterBumpWithInitialValue(): void
    {
        $c = new Counter();
        $c->setInitialValue(3);
        $this->assertEquals(DEFAULT_NAME, $c->getName());

        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->assertEquals(1, $c->getPath());
        $this->assertEquals(4, $c->getLength());

        $c->bumpBack();
        $this->assertEquals(3, $c->getValue());
        $this->assertEquals(2, $c->getPath());
        $this->assertEquals(5, $c->getLength());

        $c->bump(2);
        $this->assertEquals(5, $c->getValue());
        $this->assertEquals(4, $c->getPath());
        $this->assertEquals(7, $c->getLength());

        $c->bump(2);
        $this->assertEquals(7, $c->getValue());
        $this->assertEquals(6, $c->getPath());
        $this->assertEquals(9, $c->getLength());

        $c->bump(2);
        $this->assertEquals(9, $c->getValue());
        $this->assertEquals(8, $c->getPath());
        $this->assertEquals(11, $c->getLength());

        $c->bumpBack(2);
        $this->assertEquals(7, $c->getValue());
        $this->assertEquals(10, $c->getPath());
        $this->assertEquals(13, $c->getLength());
        $this->assertEquals(3, $c->getInitialValue());
        $this->assertEquals(4, $c->getDiff());
        $this->assertEquals(4, $c->getBumpedForward());
        $this->assertEquals(2, $c->getBumpedBack());

        $c->bump(2, false);
        $this->assertEquals(5, $c->getValue());
        $this->assertEquals(12, $c->getPath());
        $this->assertEquals(15, $c->getLength());
        $this->assertEquals(2, $c->getDiff());
        $this->assertEquals(4, $c->getBumpedForward());
        $this->assertEquals(3, $c->getBumpedBack());
    }

    /** @test */
    public function counterWithSetStep(): void
    {
        $c = (new Counter())->setStep(2);
        $this->assertEquals(DEFAULT_NAME, $c->getName());

        $c->bump();
        $this->assertEquals(2, $c->getValue());
        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->assertEquals(0, $c->getInitialValue());
        $this->assertEquals(4, $c->getDiff());

        $c = (new Counter())
            ->setStep(2)
            ->setInitialValue(3);
        $this->assertEquals(DEFAULT_NAME, $c->getName());

        $c->bump();
        $this->assertEquals(5, $c->getValue());
        $c->bump();
        $this->assertEquals(7, $c->getValue());
        $this->assertEquals(3, $c->getInitialValue());
        $this->assertEquals(4, $c->getDiff());

        $c = (new Counter(null, 1, 10))->setStep(-1);
        $c->bump();
        $this->assertEquals(9, $c->getValue());
        $c->bump();
        $this->assertEquals(8, $c->getValue());
        $this->assertEquals(10, $c->getInitialValue());
        $this->assertEquals(-2, $c->getDiff());

        $c = new Counter(self::NAME, 1, 10);
        $c->bump();
        $this->assertEquals(11, $c->getValue());
        $c->bump();
        $this->assertEquals(12, $c->getValue());
        $this->assertEquals(10, $c->getInitialValue());
        $this->assertEquals(2, $c->getDiff());

        $c = (new Counter())->setStep(-1);
        $c->bump();
        $this->assertEquals(-1, $c->getValue());
        $c->bump();
        $this->assertEquals(-2, $c->getValue());
        $this->assertEquals(0, $c->getInitialValue());
        $this->assertEquals(-2, $c->getDiff());

        $this->expectException(\RuntimeException::class);
        $c->setStep(0);
    }

    /** @test */
    public function counterWithSetInitialValue(): void
    {
        $c = (new Counter())
            ->setInitialValue(2);
        $this->assertEquals(DEFAULT_NAME, $c->getName());
        $c->bump();
        $this->assertEquals(3, $c->getValue());
        $this->assertEquals(1, $c->getBumpedForward());

        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->assertEquals(1, $c->getStep());
        $this->assertEquals(2, $c->getDiff());
        $this->assertEquals(2, $c->getBumpedForward());
        $c = (new Counter())
            ->setInitialValue(2);
        $c->bump();
        $this->assertEquals(3, $c->getValue());
        $this->assertEquals(1, $c->getBumpedForward());

        $c->bump();
        $this->assertEquals(4, $c->getValue());
        $this->assertEquals(1, $c->getStep());
        $this->assertEquals(2, $c->getDiff());
        $this->assertEquals(2, $c->getBumpedForward());
        $c->bump(1, false);
        $this->assertEquals(3, $c->getValue());
        $this->assertEquals(2, $c->getBumpedForward());
        $this->assertEquals(1, $c->getBumpedBack());

        $this->expectException(\RuntimeException::class);
        $c->setInitialValue(10);
    }

    /** @test */
    public function counterWithSetInitialValueException(): void
    {
        $c = new Counter();
        $c->bump();
        $this->assertEquals(1, $c->getValue());
        $this->expectException(\RuntimeException::class);
        $c->setInitialValue(10);
    }

    /** @test */
    public function counterWithSetStepException(): void
    {
        $c = new Counter();
        $c->bump();
        $this->assertEquals(1, $c->getValue());
        $this->expectException(\RuntimeException::class);
        $c->setStep(10);
    }

    /**
     * @test
     * @dataProvider counterWithBumpExceptionDataProvider
     * @param int $times
     */
    public function counterWithBumpException(int $times): void
    {
        $c = new Counter();
        $this->expectException(\RuntimeException::class);
        $c->bump($times);
    }

    /**
     * @test
     * @dataProvider counterDataProvider
     * @param array $expected
     * @param array $args
     */
    public function counterWithDataProvider(array $expected, array $args): void
    {
        $c = new Counter(...$args);
        $c->bump();
        [$name, $step, $initial] = $this->refineArgs(...$args);

        [$expName, $expValue, $expStep, $expInitial, $expDiff] = $expected;
        $this->assertEquals($expName, $name);
        $this->assertEquals($expValue, $c->getValue());
        $this->assertEquals($expStep, $c->getStep());
        $this->assertEquals($expStep, $step);
        $this->assertEquals($expInitial, $c->getInitialValue());
        $this->assertEquals($expInitial, $initial);
        $this->assertEquals($expDiff, $c->getDiff());
    }

    private function refineArgs($name = null, $step = null, $initial = null): array
    {
        $name = $name ?? DEFAULT_NAME;
        $step = $step ?? 1;
        $initial = $initial ?? 0;
        return
            [$name, $step, $initial];
    }

    public function counterDataProvider(): array
    {
        return [
            // [$value, $step, $initial, $diff], [$name, $step, $initial]
            [[DEFAULT_NAME, 1, 1, 0, 1], []],
            [[DEFAULT_NAME, 1, 1, 0, 1], [null]],
            [[DEFAULT_NAME, 2, 1, 1, 1], [null, 1, 1]],
            [[self::NAME, 1, 1, 0, 1], [self::NAME]],
            [['pop', 1, 1, 0, 1], ['pop']],
            [['pop', 11, 1, 10, 1], ['pop', 1, 10]],
        ];
    }

    public function counterWithBumpExceptionDataProvider(): array
    {
        return [
            [0],
            [-1],
            [-10],
        ];
    }

}