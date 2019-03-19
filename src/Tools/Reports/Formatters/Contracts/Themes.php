<?php declare(strict_types=1);

namespace AlecRabbit\Tools\Reports\Formatters\Contracts;

interface Themes
{
    public const DARK = 'dark';
    public const COMMENT = 'comment';
    public const YELLOW = 'yellow';
    public const ERROR = 'error';
    public const RED = 'red';
    public const INFO = 'info';

    public const THEMES = [
        self::DARK => 'dark',
        self::COMMENT => 'yellow',
        self::YELLOW => 'yellow',
        self::INFO => 'green',
        self::RED => 'red',
        self::ERROR => 'red',
    ];
}
