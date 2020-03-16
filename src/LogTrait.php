<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHPLog;

use Cake\Log\Log;
use Psr\Log\LogLevel;
use Tyrellsys\CakePHPLog\Formatter;

/**
 * A trait providing an object short-cut method
 * to logging.
 */
trait LogTrait
{

    /**
     * Convenience method to write a message to Log. See Log::write()
     * for more information on writing to logs.
     *
     * @param string $message Log message.
     * @param int|string $level Error level.
     * @param string|array $context Additional log data relevant to this message.
     * @return bool Success of log write.
     */
    public function log($message, $level = LogLevel::ERROR, $context = []): bool
    {
        return Log::write($level, Formatter::getMessage($message), $context);
    }
}
 
