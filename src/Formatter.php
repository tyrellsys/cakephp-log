<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHPLog;

use Cake\Datasource\EntityInterface;
use Cake\Error\Debugger;
use JsonSerializable;

class Formatter
{
    /**
     * @param string|array $data data
     * @return string
     */
    public static function getMessage($data): string
    {
        $isArray = is_array($data);

        $data = (array)$data;

        $result = [];
        foreach ($data as $key => $record) {
            if (is_string($record)) {
                $result[$key] = $record;
                continue;
            }

            $isObject = is_object($record);

            if ($isObject && $record instanceof EntityInterface) {
                $result[$key] = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                continue;
            }

            if ($isObject && method_exists($record, '__toString')) {
                $result[$key] = (string)$record;
                continue;
            }

            if ($isObject && $record instanceof JsonSerializable) {
                $result[$key] = json_encode($record, JSON_UNESCAPED_UNICODE);
                continue;
            }

            $result[$key] = print_r($record, true);
        }

        $message = static::_prefix();
        if ($isArray) {
            $message .= print_r($result, true);
        } else {
            $message .= current($result);
        }
        $message .= static::_suffix();

        return $message;
    }

    /**
     *
     * @return string
     */
    protected static function _prefix(): string
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $found = null;
        $dirname = dirname(__DIR__);
        $length = strlen($dirname);
        $before = null;
        foreach ($traces as $trace) {
            if (substr($trace['file'], 0, $length) !== $dirname) {
                $found = preg_match('#^' . preg_quote(APP) . '#', $trace['file']) ? $trace : $before;
                break;
            }
            $before = $trace;
        }

        // [hostname]:path/to/caller_filename:[caller line no](pid):
        return '[' . php_uname('n') . ']:' .
            Debugger::trimPath($found['file']) .
            '(' . $found['line'] . ')' .
            '[' . getmypid() . ']: ';
    }

    /**
     *
     * @return string
     */
    protected static function _suffix(): string
    {
        return "";
    }
}
