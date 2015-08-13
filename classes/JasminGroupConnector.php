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
 */
include('classes/JasminConnector.php');

/**
 * Class JasminGroupConnector
 *
 * Usage:
 * $groupcon = new JasminGroupConnector();
 * $groupcon->gid = 'customers';
 * $groupcon->save();
 *
 * or
 *
 * $groupcon->delete();
 */
class JasminGroupConnector extends JasminConnector
{
    var $gid = null;
    var $telnet;

    public function __construct()
    {
        $this->telnet = new TelnetConnector();
        $this->telnet->connect('127.0.0.1', 8990, 'jcliadmin', 'jclipwd');
    }

    /**
     * getgroups()
     *
     * Get a string containing all the groups
     *
     * TODO: find a way to return an array with the group ids.
     *
     * @return null|string
     * @throws Exception
     */
    public function getgroups()
    {
        if (!empty($this->telnet))
        {
            $result = $this->telnet->doCommand('group -l');
            if (is_null($result))
            {
                throw new Exception('GroupConnectorFailed');
            }

        } else
        {
            throw new Exception('ConnectionNotAvailabe');
        }

        return $result;
    }

    /**
     * save()
     *
     * Saves the group id
     *
     * @return null|string
     * @throws Exception
     */
    public function save()
    {
        if (!empty($this->gid))
        {
            $result = $this->telnet->doCommand('group -a');
            $result .= $this->telnet->doCommand("gid " . $this->gid);
            $result .= $this->telnet->doCommand("ok");

            //echo $result;
        } else
        {
            throw new Exception('NullGIDException');
        }

        return $result;
    }

    /**
     * delete()
     *
     * Deletes the designated group id
     *
     * @return null|string
     * @throws Exception
     */
    public function delete()
    {
        if (!empty($this->gid))
        {
            $result = $this->telnet->doCommand('group -r ' . $this->gid);
        } else
        {
            throw new Exception('NullGIDException');
        }

        return $result;

    }

    public function __destruct()
    {
        $this->telnet->disconnect();
    }
}