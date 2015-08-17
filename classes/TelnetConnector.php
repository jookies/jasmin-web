<?php

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 */
class TelnetConnector
{
    var $sleeptime = 125000;
    var $loginsleeptime = 1000000;
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
        if (version_compare(PHP_VERSION, $neededphpvers, '<'))
        {
            throw new Exception('LowPhpVersionException');
        }
        if (!is_int($port))
        {
            throw new Exception('InvalidPortFormatException');
        }
        if (!filter_var($server, FILTER_VALIDATE_IP))
        {
            throw new Exception('InvalidServerIPFormatException');
        }
        if (empty($user) || empty($pass))
        {
            throw new Exception('NullUserPassException');
        }

        if ($this->fp = fsockopen($server, $port))
        {

            $response = $this->getResponse();

            $response = explode("\n", $response);
            $this->loginprompt = $response[count($response) - 1];

            fputs($this->fp, "$user\r");
            usleep($this->sleeptime);

            fputs($this->fp, "$pass\r");
            usleep($this->sleeptime);

            $response = $this->getResponse();
            $response = explode("\n", $response);

            if (($response[count($response) - 1] == '') || ($this->loginprompt == $response[count($response) - 1]))
            {
                throw new Exception('LoginFailedException');
            }
        } else
        {
            throw new Exception('UnableToOpenConnectionException');
        }
    }

    /**
     * getInstance()
     *
     * singleton for Telnetconnector
     *
     * @return TelnetConnector
     */
    public static function getInstance($server = '127.0.0.1', $port = 8990, $user = null, $pass = null)
    {
        if (!self::$instance)
        {
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
        if ($this->fp)
        {
            $this->doCommand('exit');
            fclose($this->fp);
            $this->fp = null;
        } else
        {
            throw new Exception('NoAvailableConnectionException');
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

        if ($this->fp)
        {
            fwrite($this->fp, $command);

            usleep($this->sleeptime);
            $response = $this->getResponse();
            $this->global_buffer .= $response;

            return $response;
        }

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
        /*$res = '';
        do
        {
            $res .= fread($this->fp, 1000);
            $s = socket_get_status($this->fp);
        } while ($s['unread_bytes']);

        return $res;
*/
        usleep($this->sleeptime);
        $c = fread($this->fp, 8192);


        //$c = "";
        //while (!feof($this->fp)) {
        //   $c .= fgets($this->fp, 1024)."<BR>\n";
        //}

        return $c;

    }
}