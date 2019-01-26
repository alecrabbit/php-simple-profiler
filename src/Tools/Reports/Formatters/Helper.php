<?php

declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters;

class Helper
{
    /**
     * @param float $relative
     * @param null|string $prefix
     * @return string
     */
    public static function percent(float $relative, ?string $prefix = null): string
    {
        $prefix = $prefix ?? '';
        return
            $prefix . number_format($relative * 100, 1) . '%';
    }
}
