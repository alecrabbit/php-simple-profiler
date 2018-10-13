<?php
/**
 * User: alec
 * Date: 13.10.18
 * Time: 23:20
 */

namespace AlecRabbit;


class Profiler
{
    public function __construct()
    {
        $this->value = 5;
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}