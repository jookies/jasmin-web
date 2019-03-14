<?php

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Exception\ConnectionException;

class SocketConnection
{
    /**
     * Time for sleeping between command
     * @var int
     */
    const DEFAULT_SLEEP_TIME = 500000; //half second

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
        $this->read();
    }

    /**
     * @param string $host
     *
     * @param int $port
     *
     * @param int $waitTime
     *
     * @return SocketConnection
     *
     * @throws ConnectionException
     */
    public static function init(string $host, int $port, int $waitTime = self::DEFAULT_SLEEP_TIME): SocketConnection
    {
        if (!is_int($port)) {
            throw new ConnectionException('Invalid port');
        }

        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            throw new ConnectionException('Invalid server ip');
        }

        $start = -microtime(true);

        if (!$resource = @stream_socket_client("tcp://$host:$port", $errno, $errstr)) {
            throw new \RuntimeException($errstr);
        }

        $start += microtime(true);
        $start *= 1000;

        $rwtimeout = (float) $start;
        $rwtimeout = $rwtimeout > 0 ? $rwtimeout : -1;
        $timeoutSeconds = floor($rwtimeout);
        $timeoutUSeconds = ($rwtimeout - $timeoutSeconds) * 1000000;
        stream_set_timeout($resource, $timeoutSeconds, $timeoutUSeconds);

        return new self($resource, $waitTime  < self::DEFAULT_SLEEP_TIME ? self::DEFAULT_SLEEP_TIME : $waitTime);
    }

    /**
     * @param string $str
     * @throws \Exception
     */
    public function write(string $str)
    {
        while (($length = strlen($str)) > 0) {
            $written = @fwrite($this->fp, $str);

            if ($length === $written) {
                return;
            }

            if ($written === false || $written === 0) {
                throw new \RuntimeException('Error while writing bytes to the server.');
            }

            $str = substr($str, $written);
        }
    }

    /**
     * @param int|null $bytes
     * @return bool|string
     */
    public function read(int $bytes = null)
    {
        return fread($this->fp, $bytes ?? 8192);
    }

    public function disconnect()
    {
        fclose($this->fp);
        $this->fp = null;
    }

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->fp !== null;
    }

    public function wait()
    {
        usleep($this->sleepTime);
    }
}