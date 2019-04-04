<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Spinner\Core;

interface SpinnerInterface
{
    /**
     * @return string
     */
    public function begin(): string;

    /**
     * @return string
     */
    public function spin(): string;

    /**
     * @return string
     */
    public function end(): string;
}