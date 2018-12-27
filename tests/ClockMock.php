<?php
/**
 * User: alec
 * Date: 26.12.18
 * Time: 17:27
 */
declare(strict_types=1);

namespace Tests;

class ClockMock extends \Symfony\Bridge\PhpUnit\ClockMock
{
    protected static $now;

    public static function withClockMock($enable = null)
    {
        if (null === $enable) {
            return null !== self::$now;
        }

        self::$now = is_numeric($enable) ? (float)$enable : ($enable ? microtime(true) : null);
        dump('>>>>' . self::$now);
    }

    public static function sleep($s): int
    {
        dump(__METHOD__);
        if (null === self::$now) {
            dump('NULL');
            return \sleep($s);
        }

        self::$now += (int)$s;
        dump(self::$now);

        return 0;
    }

    public static function usleep($us): void
    {
        dump(__METHOD__);

        if (null === self::$now) {
            dump('NULL');

            \usleep($us);
        }

        self::$now += $us / 1000000;
        dump(self::$now);

    }

    public static function microtime($asFloat = false)
    {
        dump(__METHOD__);

        if (null === self::$now) {
            dump('NULL');

            return \microtime($asFloat);
        }
        dump(self::$now);

        if ($asFloat) {
            return self::$now;
        }

        return sprintf('%0.6f00 %d', self::$now - (int)self::$now, (int)self::$now);
    }

    public static function date($format, $timestamp = null)
    {
        dump(__METHOD__);

        if (null === $timestamp) {
            $timestamp = self::time();
        }

        return \date($format, $timestamp);
    }

    public static function time()
    {
        dump(__METHOD__);

        if (null === self::$now) {
            dump('NULL');

            return \time();
        }
        dump('>>>>>>>>>>>>>', self::$now);

        return (int)self::$now;
    }

    public static function register($class)
    {
        $self = \get_called_class();
        dump($self);

        $mockedNs = [substr($class, 0, strrpos($class, '\\'))];
        if (0 < strpos($class, '\\Tests\\')) {
            $ns = str_replace('\\Tests\\', '\\', $class);
            $mockedNs[] = substr($ns, 0, strrpos($ns, '\\'));
        } elseif (0 === strpos($class, 'Tests\\')) {
            $mockedNs[] = substr($class, 6, strrpos($class, '\\') - 6);
        }
        dump($mockedNs);
        foreach ($mockedNs as $ns) {
            dump($ns);
            if (\function_exists($ns . '\time')) {
                dump('already registered');
                continue;
            }
            dump('registering');
            eval(<<<EOPHP
namespace $ns;

function time()
{
    return \\$self::time();
}

function microtime(\$asFloat = false)
{
    return \\$self::microtime(\$asFloat);
}

function sleep(\$s)
{
    return \\$self::sleep(\$s);
}

function usleep(\$us)
{
    return \\$self::usleep(\$us);
}

function date(\$format, \$timestamp = null)
{
    return \\$self::date(\$format, \$timestamp);
}

EOPHP
            );
        }
    }

}