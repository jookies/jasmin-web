<?php

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Exception\ConnectionException;

class SocketConnection
{
    /**
     * Time for sleeping between command
     * @var int
     */
    const DEFAULT_SLEEP_TIME = 10000;
    const LOGIN_SLEEP_TIME = 100000;
    const DEFAULT_BUFFER_SIZE = 2048;

    /**
     * @var resource
     */
    private $fp;

    /**
     * Time for sleeping between command
     *
     * @var int
     */
    private $sleepTime;

    /**
     * SocketConnection constructor.
     * @param resource $fp
     * @param int $sleepTime
     */
    private function __construct($fp, int $sleepTime)
    {
        $this->fp = $fp;
        $this->sleepTime = $sleepTime;
    }

    /**
     * @param string $host
     * @param int $port
     * @param int $sleepTime Time for sleeping between command
     *
     * @return SocketConnection
     *
     * @throws ConnectionException
     */
    public static function init(string $host, int $port, int $sleepTime = self::DEFAULT_SLEEP_TIME): SocketConnection
    {
        if (!is_int($port)) {
            throw new ConnectionException('Invalid port');
        }

        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            throw new ConnectionException('Invalid server ip');
        }

        $errno = $errstr = null;
        set_error_handler(function ($_errno, $_errstr) use (&$errno, &$errstr) {
            $errno = $_errno;
            $errstr = $_errstr;
        }, E_WARNING);

        $fp = fsockopen($host, $port, $errno, $errstr);

        restore_error_handler();

        if (!$fp) {
            throw new ConnectionException('Unable open connection, errno: ' . $errno . ', errstr: ' . $errstr);
        }

        return new self($fp, $sleepTime < self::DEFAULT_SLEEP_TIME ? self::DEFAULT_SLEEP_TIME : $sleepTime);
    }

    /**
     * @param string $str
     * @param bool $needSleep
     */
    public function write(string $str, bool $needSleep = true)
    {
        fwrite($this->fp, $str);
        if ($needSleep) {
            usleep($this->sleepTime);
        }
    }

    /**
     * @param int|null $bytes
     * @return bool|string
     */
    public function read(int $bytes = null)
    {
        return str_replace('jcli: >', '', fread($this->fp, $bytes ?? self::DEFAULT_BUFFER_SIZE));
    }

    public function disconnect()
    {
        $this->fp = null;
    }

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->fp !== null;
    }
}