<?php
namespace Jookies;

/**
 * Class JasminObject
 *
 * Provides an entry point for all jasmin cli modules.
 *
 */
class JasminObject extends JasminConnector
{
    var $id;
    var $command;
    var $properties;
    var $telnet;

    public function __construct()
    {
        try {
            $this->telnet = TelnetConnector::getInstance('127.0.0.1', 8990, 'jcliadmin', 'jclipwd');
        } catch (Exception $e) {
            echo('Telnet connection failed :' . $e->getMessage());

            return false;
        }

        return true;
    }

    public function __destruct()
    {

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
        //echo "edo:" . strtolower($result);
        if (strstr(strtolower($result),'successfully')) {
            echo $result;

            return true;
        }

        return false;
//        return $result;
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
        $result = $this->telnet->doCommand($this->command . ' -r ' . $this->id);

        return $result;
    }

    /**
     * show()
     *
     * Shows information about the object
     *
     * @return null|string
     */
    public function show()
    {
        $result = $this->telnet->doCommand($this->command . ' -s ' . $this->id);

        return $result;
    }

    /**
     * update()
     *
     * Update information about the object
     * @return null|string
     */
    public function update()
    {
        $result = $this->telnet->doCommand($this->command . ' -u ' . $this->id);

        return $result;
    }

    /**
     * flush()
     *
     * Flashes the table of the object
     * @return null|string
     */
    public function flush()
    {
        $result = $this->telnet->doCommand($this->command . ' -f');

        return $result;
    }
}