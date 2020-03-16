<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHPLog;

use Cake\Log\Log as CakeLog;
use Tyrellsys\CakePHPLog\Formatter;

class Log
{
    const WRITER_METHODS = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
    ];

    /**
     * __callStatic php's magic method
     *
     * Proxy for Cake\Log\Log method
     *
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if ($name === 'write') {
            return CakeLog::write($arguments[0], Formatter::getMessage($arguments[1]), $arguments[2] ?? []);
        } elseif (in_array($name, self::WRITER_METHODS, true)) {
            return CakeLog::{$name}(Formatter::getMessage($arguments[0]), $arguments[1] ?? []);
        } else {
            return CakeLog::{$name}(...$arguments);
        }
    }
}

