<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Spinner;

use AlecRabbit\Accessories\Circular;
use AlecRabbit\Tools\Spinner\Core\AbstractSpinner;

class ClockSpinner extends AbstractSpinner
{
    /**
     * @return Circular
     */
    protected function getSymbols(): Circular
    {
        return new Circular([
            '🕐',
            '🕑',
            '🕒',
            '🕓',
            '🕔',
            '🕕',
            '🕖',
            '🕗',
            '🕘',
            '🕙',
            '🕚',
            '🕛',
//            '🕜',
//            '🕝',
//            '🕞',
//            '🕟',
//            '🕠',
//            '🕡',
//            '🕢',
//            '🕣',
//            '🕤',
//            '🕥',
//            '🕦',
        ]);
    }

    protected function getStyles(): ?Circular
    {
        return null;
    }
}
