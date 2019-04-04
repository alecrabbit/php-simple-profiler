<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Spinner\Core;

use AlecRabbit\Accessories\Circular;
use AlecRabbit\ConsoleColour\Terminal;
use AlecRabbit\Tools\Contracts\Strings;

abstract class AbstractSpinner implements SpinnerInterface
{
    protected const PADDING_STR = ' ';

    /** @var Circular */
    protected $spinnerSymbols;
    /** @var null|Circular */
    protected $styles;
    /** @var string */
    protected $str;
    /** @var string */
    protected $resetStr;
    /** @var \Closure */
    protected $style;


    public function __construct(string $str = '', string $prefix = ' ', string $suffix = '...')
    {
        $this->spinnerSymbols = $this->getSymbols();
        $this->styles = $this->getStyles();

        $this->str = $this->refineStr($str, $prefix, $suffix);
        $strLen = strlen($this->str . static::PADDING_STR) + 2;
        $this->resetStr = Strings::ESC . "[{$strLen}D";
        $this->style = $this->getStyle();
    }

    /**
     * @return Circular
     */
    abstract protected function getSymbols(): Circular;

    protected function getStyles(): ?Circular
    {
        $terminal = new Terminal();
        if ($terminal->supports256Color()) {
            return new Circular([
                '38;5;197',
                '38;5;198',
                '38;5;199',
                '38;5;200',
                '38;5;201',
                '38;5;202',
                '38;5;203',
                '38;5;204',
                '38;5;205',
            ]);
        }
        if ($terminal->supportsColor()) {
            return new Circular([
                '96',
            ]);
        }
        return null;
    }

    /**
     * @param string $str
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    protected function refineStr(string $str, string $prefix, string $suffix): string
    {
        return $prefix . $str . $suffix;
    }

    /**
     * @return \Closure
     */
    protected function getStyle(): \Closure
    {
        if (null === $this->styles) {
            return
                function (): string {
                    return static::PADDING_STR . $this->spinnerSymbols->value();
                };
        }
        return
            function (): string {
                return
                    static::PADDING_STR .
                    Strings::ESC .
                    "[{$this->styles->value()}m{$this->spinnerSymbols->value()}" .
                    Strings::ESC . '[0m';
            };
    }

    /** {@inheritDoc} */
    public function begin(): string
    {
        return $this->work() . $this->resetStr;
    }

    protected function work(): string
    {
        return ($this->style)() . $this->str;
    }

    /** {@inheritDoc} */
    public function spin(): string
    {
        return $this->work() . $this->resetStr;
    }

    /** {@inheritDoc} */
    public function end(): string
    {
        return $this->resetStr . Strings::ESC . '[K';
    }

}