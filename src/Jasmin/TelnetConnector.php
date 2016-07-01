<?php namespace JasminWeb\Jasmin;

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 17.06.16
 */

use JasminWeb\Exception\ConnectionException;
use JasminWeb\Exception\ConnectorException;

class TelnetConnector
{
    /**
     * Socket connection
     * @var null|resource
     */
    protected $fp = null;

    /**
     * Time for sleeping between command
     * @var int
     */
    protected $sleeptime = 125000;
    protected $loginsleeptime = 100000;
    protected $global_buffer;
    protected $loginprompt;

    public static $defaultBufferSize = 2048;

    /**
     * TelnetConnector constructor.
     */
    protected function __construct(){

    }

    /**
     * TelnetConnector initializer.
     * @param string $username Username for connection
     * @param string $password Password for connection
     * @param string $server ip for connection
     * @param int $port Port for connection
     * @return TelnetConnector
     * @throws ConnectionException
     */
    public static function init($username, $password , $server = '127.0.0.1', $port = 8990)
    {
        if (!is_int($port)) {
            throw new ConnectionException('Invalid port');
        }
        if (!filter_var($server, FILTER_VALIDATE_IP)) {
            throw new ConnectionException('Invalid server ip');
        }

        $username = trim($username);
        $password = trim($password);
        if (empty($username) || empty($password)) {
            throw new ConnectionException('Not set username or password');
        }

        $connector = new self();

        if ($connector->fp = fsockopen($server, $port)) {
            fwrite($connector->fp, "$username\r");
            usleep($connector->sleeptime);
            fwrite($connector->fp, "$password\r");
            usleep($connector->sleeptime);
            fwrite($connector->fp, chr(0));
            usleep($connector->sleeptime);
        } else {
            throw new ConnectionException('Unable open connection');
        }
        return $connector;
    }

    /**
     * Gets the response string from the server.
     * @return string
     * @throws ConnectorException
     */
    public function getResponse()
    {
        if (!$this->fp) {
            throw new ConnectorException('Try execute command without open socket');
        }
        $buffer = trim(fread($this->fp, self::$defaultBufferSize));
        return $buffer;
    }


    /**
     * Executes the inserted command to jasmin cli.
     *
     * @param $command
     * @return string
     * @throws ConnectorException
     */
    public function doCommand($command)
    {
        if (!$this->fp) {
            throw new ConnectorException('Try execute command without open socket');
        }

        $command = trim($command) . "\r";

        fwrite($this->fp, $command);
        fwrite($this->fp, " ");
        usleep($this->sleeptime);

        return $this->getResponse();
    }

    public function disconnect()
    {
        $this->doCommand('quit');
        $this->fp = null;
    }

    public function persist()
    {
        $this->doCommand('persist');
    }
}