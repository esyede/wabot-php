<?php

namespace Esyede\Wabot\Helpers;

use DateTime;
use DateTimeZone;

class Log
{
    private $logDirectory;
    private $timezone = 'Asia/Jakarta';

    public function __construct($logDirectory, $timezone = 'Asia/Jakarta')
    {
        $logDirectory = rtrim(rtrim($logDirectory, '/'), DIRECTORY_SEPARATOR);
        $this->logDirectory = $logDirectory;
        $this->timezone = $timezone;
    }

    public function write($message)
    {
        $message = sprintf('[%s] %s', $this->date('Y-m-d H:i:s'), $message) . PHP_EOL;
        $path = $this->logDirectory . DIRECTORY_SEPARATOR . $this->date('Y-m-d') . '.log';

        if (is_file($path)) {
            file_put_contents($path, $message, LOCK_EX | FILE_APPEND);
        } else {
            file_put_contents($path, $message, LOCK_EX);
        }
    }

    private function format($message)
    {
        return sprintf('[%s] %s', $this->date('Y-m-d H:i:s'), $message) . PHP_EOL;
    }

    private function date($format)
    {
        return (new DateTime('now', new DateTimeZone($this->timezone)))
            ->format($format);
    }
}
