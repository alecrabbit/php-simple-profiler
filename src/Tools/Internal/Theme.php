<?php
/**
 * User: alec
 * Date: 24.12.18
 * Time: 15:17
 */
declare(strict_types=1);

namespace AlecRabbit\Tools\Internal;

use AlecRabbit\ConsoleColour;

class Theme extends ConsoleColour implements ThemesInterface
{
    private $do;

    public function __construct($color = false)
    {
        $this->do = $color;
        parent::__construct();
        $this->setDefaultThemes();
    }

    protected function setDefaultThemes(): void
    {
        $this->addTheme(static::DARK, 'dark');
        $this->addTheme(static::COMMENT, 'yellow');
        $this->addTheme(static::INFO, 'green');
        $this->addTheme(static::RED, 'red');
    }

    public function comment($text): string
    {
        return
            $this->apply(static::COMMENT, $text);
    }

    public function yellow($text): string
    {
        return
            $this->apply(static::COMMENT, $text);
    }

    public function apply($style, $text): string
    {
        return $this->do ? parent::apply($style, $text) : (string)$text;
    }

    public function red($text): string
    {
        return
            $this->apply(static::RED, $text);
    }

    public function info($text): string
    {
        return
            $this->apply(static::INFO, $text);
    }

    public function green($text): string
    {
        return
            $this->apply(static::INFO, $text);
    }

    public function dark($text): string
    {
        return
            $this->apply(static::DARK, $text);
    }
}
