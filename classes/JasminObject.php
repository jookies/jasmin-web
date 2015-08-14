<?php

include('classes/JasminConnector.php');

class JasminObject extends JasminConnector
{
    var $key;
    var $command;
    var $properties;
    var $telnet;

    public function __construct()
    {
        try {
            $this->telnet = new TelnetConnector('127.0.0.1', 8990, 'jcliadmin', 'jclipwd');
        } catch (Exception $e) {
            echo('Telnet connection failed :' . $e->getMessage());

            return false;
        }
    }

    public function __destruct()
    {
        $this->telnet->disconnect();
    }

    /**
     * getAll()
     *
     * Get an array containing all the requested entities
     *
     * TODO: find a way to return an array with the entities.
     *
     * @return array
     */
    public function getAll()
    {
        $result = $this->telnet->doCommand($this->command . ' -l');

        return $result;
    }

    /**
     * save()
     *
     * Saves the properties for the given command (user, group, smppc, etc.)
     * Example:
     *  jcli: group -a
     *  Adding a new Group: (ok: save, ko: exit)
     *  > gid customers
     *  > ok
     *  Successfully added Group [customers]
     *
     * @return bool
     */
    public function save()
    {
        $this->telnet->doCommand($this->command . ' -a');

        foreach ($this->properties as $property_key => $property_value) {
            $this->telnet->doCommand($property_key . ' ' . $property_value);
        }
        $result = $this->telnet->doCommand('ok');

        return $result;
    }

    /**
     * delete()
     *
     * Deletes the key for the given command
     *
     * Example:
     *  jcli: group -r customers
     *  Successfully removed Group id:customers
     *
     * @return null|string
     */
    public function delete()
    {
        $result = $this->telnet->doCommand($this->command . ' -r ' . $this->key);

        return $result;
    }
}