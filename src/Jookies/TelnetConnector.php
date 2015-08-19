<?php

namespace Jookies;

class TelnetConnector
{
    var $sleeptime = 125000;
    var $loginsleeptime = 100000;
    var $global_buffer;
    var $fp = null;
    var $loginprompt;
    private static $instance;

    /**
     * __construct()
     *
     * constructor for telnetconnector that connects to telnet server for jasmin.
     *
     * @param string $server
     * @param int $port
     * @param null $user
     * @param null $pass
     * @throws Exception
     */
    public function __construct($server = '127.0.0.1', $port = 8990, $user = null, $pass = null)
    {
        $neededphpvers = '5.3.0';
        if (version_compare(PHP_VERSION, $neededphpvers, '<')) {
            throw new \Exception('LowPhpVersionException');
        }
        if (!is_int($port)) {
            throw new \Exception('InvalidPortFormatException');
        }
        if (!filter_var($server, FILTER_VALIDATE_IP)) {
            throw new \Exception('InvalidServerIPFormatException');
        }
        if (empty($user) || empty($pass)) {
            throw new \Exception('NullUserPassException');
        }

        if ($this->fp = fsockopen($server, $port)) {


            fwrite($this->fp, "$user\r");
            usleep($this->sleeptime);
            //echo fgets($this->fp, 8192);
            fwrite($this->fp, "$pass\r");

            usleep($this->sleeptime);
            fwrite($this->fp, chr(0));
            //echo fgets($this->fp, 8192);

            $response = $this->getResponse();

            //var_dump($response);


        } else {
            throw new \Exception('UnableToOpenConnectionException');
        }
    }

    /**
     * getInstance()
     *
     * singleton for Telnetconnector
     *
     * @param string $server
     * @param int $port
     * @param null $user
     * @param null $pass
     * @return TelnetConnector
     */
    public static function getInstance($server = '127.0.0.1', $port = 8990, $user = null, $pass = null)
    {
        if (!self::$instance) {
            self::$instance = new TelnetConnector($server, $port, $user, $pass);
        }

        return self::$instance;
    }

    /**
     * __destruct()
     *
     * Executes the exit command on class destruction.
     *
     * @throws Exception
     */
    public function __destruct()
    {
        if ($this->fp) {
            //$this->doCommand('quit');
            fclose($this->fp);
            $this->fp = null;
        } else {
            throw new \Exception('NoAvailableConnectionException');
        }
    }

    /**
     * doCommand($command)
     *
     * Executes the inserted command to jasmin cli.
     *
     * @param $command
     * @return null|string
     */
    public function doCommand($command)
    {
        $command .= "\r";

        $response = null;

        if ($this->fp) {
            fwrite($this->fp, $command);
            fwrite($this->fp, " ");
            usleep($this->sleeptime);

            $response = $this->getResponse();
        }

        return $response;
    }

    /**
     * getResponse()
     *
     * Gets the response string from the server.
     * TODO: doesn't work as intended. It doesn't return the proper telnet response string.
     * @return string
     */
    public function getResponse()
    {


        usleep($this->sleeptime);

        $buffer = trim(fread($this->fp, 2048));
        // Cut last line from buffer (almost always prompt)
        //$buffer = explode("\n", $buffer);
        //unset($buffer[count($buffer) - 1]);
        var_dump($buffer);
        //$buffer = implode("\n", $buffer);


        //$c = "";
        //while (!feof($this->fp)) {
        //   $c .= fgets($this->fp, 1024)."<BR>\n";
        //}

        return $buffer;

    }
}