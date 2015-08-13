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

    var $fp = null;
    var $loginprompt;

    var $conn1;
    var $conn2;

    public function __construct()
    {
        $this->conn1 = chr(0xFF) . chr(0xFB) . chr(0x1F) . chr(0xFF) . chr(0xFB) .
            chr(0x20) . chr(0xFF) . chr(0xFB) . chr(0x18) . chr(0xFF) . chr(0xFB) .
            chr(0x27) . chr(0xFF) . chr(0xFD) . chr(0x01) . chr(0xFF) . chr(0xFB) .
            chr(0x03) . chr(0xFF) . chr(0xFD) . chr(0x03) . chr(0xFF) . chr(0xFC) .
            chr(0x23) . chr(0xFF) . chr(0xFC) . chr(0x24) . chr(0xFF) . chr(0xFA) .
            chr(0x1F) . chr(0x00) . chr(0x50) . chr(0x00) . chr(0x18) . chr(0xFF) .
            chr(0xF0) . chr(0xFF) . chr(0xFA) . chr(0x20) . chr(0x00) . chr(0x33) .
            chr(0x38) . chr(0x34) . chr(0x30) . chr(0x30) . chr(0x2C) . chr(0x33) .
            chr(0x38) . chr(0x34) . chr(0x30) . chr(0x30) . chr(0xFF) . chr(0xF0) .
            chr(0xFF) . chr(0xFA) . chr(0x27) . chr(0x00) . chr(0xFF) . chr(0xF0) .
            chr(0xFF) . chr(0xFA) . chr(0x18) . chr(0x00) . chr(0x58) . chr(0x54) .
            chr(0x45) . chr(0x52) . chr(0x4D) . chr(0xFF) . chr(0xF0);
        $this->conn2 = chr(0xFF) . chr(0xFC) . chr(0x01) . chr(0xFF) . chr(0xFC) .
            chr(0x22) . chr(0xFF) . chr(0xFE) . chr(0x05) . chr(0xFF) . chr(0xFC) . chr(0x21);
    }

    /**
     * connect()
     *
     * Connects to telnet server for jasmin.
     *
     * @param string $server
     * @param int $port
     * @param null $user
     * @param null $pass
     * @throws Exception
     */
    public function connect($server = '127.0.0.1', $port = 8990, $user = null, $pass = null)
    {
        $neededphpvers = '5.1.0';
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
            fputs($this->fp, $this->conn1);
            usleep($this->sleeptime);

            fputs($this->fp, $this->conn2);
            usleep($this->sleeptime);
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
     * disconnect()
     *
     * Executes the exit command.
     *
     * @throws Exception
     */
    public function disconnect()
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
        $response = null;
        if ($this->fp)
        {
            fputs($this->fp, "$command\r\n");
            usleep($this->sleeptime);
            $response = $this->getResponse();

            //$response = preg_replace("/^.*?\n(.*)\n[^\n]*$/", "<br/>", $response);

            return $response;
        }

    }

    /**
     * getResponse()
     *
     * Gets the response string from the server.
     *
     * TODO: find a better way to read the socket. socket_get_Status['unread_bytes'] should not be used like that.
     *
     * @return string
     */
    public function getResponse()
    {
        $res = '';
        do
        {
            $res .= fread($this->fp, 1000);
            $s = socket_get_status($this->fp);
        } while ($s['unread_bytes']);

        return $res;
    }
}