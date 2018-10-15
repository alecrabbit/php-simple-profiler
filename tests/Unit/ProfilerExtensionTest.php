<?php
/**
 * User: alec
 * Date: 14.10.18
 * Time: 21:28
 */

namespace Unit;


use AlecRabbit\Profiler\Profiler;
use PHPUnit\Framework\TestCase;


class ProfilerExt extends Profiler {
    protected function formatName($name, $suffixes): string
    {
        return
            sprintf('%s >> %s <<', $name, implode(', ', $suffixes));
    }

}

class ProfilerExtensionTest extends TestCase
{
    /** @test */
    public function ClassCreation()
    {
        $profiler = new Profiler();
        $this->assertInstanceOf(Profiler::class, $profiler);
        $this->assertEquals('default [new]', $profiler->timer(null, 'new')->getName());
        $this->assertEquals('default [new]', $profiler->counter(null, 'new')->getName());
        $profiler = new ProfilerExt();
        $this->assertInstanceOf(ProfilerExt::class, $profiler);
        $this->assertEquals('default >> new <<', $profiler->timer(null, 'new')->getName());
        $this->assertEquals('default >> new <<', $profiler->counter(null, 'new')->getName());
    }
}

