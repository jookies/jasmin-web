<?php

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Exception\ConnectionException;

class SocketConnection
{
    /**
     * Time for sleeping between command
     * @var int
     */
    const SLEEP_TIME = 125000;
    const LOGIN_SLEEP_TIME = 100000;
    const DEFAULT_BUFFER_SIZE = 2048;

    /**
     * @var resource
     */
    private $fp;

    /**
     * SocketConnection constructor.
     * @param resource $fp
     */
    private function __construct($fp)
    {
        $this->fp = $fp;
    }

    /**
     * @param string $host
     * @param int $port
     *
     * @return SocketConnection
     *
     * @throws ConnectionException
     */
    public static function init(string $host, int $port): SocketConnection
    {
        if (!is_int($port)) {
            throw new ConnectionException('Invalid port');
        }

        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            throw new ConnectionException('Invalid server ip');
        }

        $fp = fsockopen($host, $port, $errno, $errstr);
        if (!$fp) {
            throw new ConnectionException('Unable open connection, errno: ' . $errno . ', errstr: ' . $errstr);
        }

        return new self($fp);
    }

    /**
     * @param string $str
     * @param bool $needSleep
     */
    public function write(string $str, bool $needSleep = true)
    {
        fwrite($this->fp, $str);
        if ($needSleep) {
            usleep(self::SLEEP_TIME);
        }
    }

    /**
     * @param int|null $bytes
     * @return bool|string
     */
    public function read(int $bytes = null)
    {
        return str_replace('>', '', fread($this->fp, $bytes ?? self::DEFAULT_BUFFER_SIZE));
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