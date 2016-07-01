<?php namespace JasminWeb\Jasmin;

/**
 * Class BaseObject
 * @package JasminWeb\Jasmin
 *
 * Base class for all other entity
 */
abstract class BaseObject
{
    /**
     * Telnet connector to jasmin db
     * @var TelnetConnector
     */
    protected $connector;

    /**
     * Command for manipulate with data
     * @var string
     */
    protected $command;

    /**
     * Property of entity
     * @var array
     */
    public $attributes = [];

    /**
     * Required field for save at db
     * @var array
     */
    protected $requiredAttributes = [];

    /**
     * Errors at model
     * @var array
     */
    public $errors = [];

    /**
     * BaseObject constructor.
     * @param TelnetConnector $connector
     */
    public function __construct(TelnetConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Get an string with containing all the requested entities
     *
     * @return string
     */
    public function getAll()
    {
        return $this->connector->doCommand($this->command . ' -l');
    }

    /**
     * Saves the attributes for the given command
     *
     * @return bool
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function save()
    {
        if (!$this->checkRequiredAttribute()) {
            return false;
        }
        $this->connector->doCommand($this->command . ' -a');

        foreach ($this->attributes as $property_key => $property_value) {
            $this->connector->doCommand($property_key . ' ' . $property_value);
        }
        $result = $this->connector->doCommand('ok');
        if (!!strstr(strtolower($result), 'successfully added')) {
            return true;
        } else {
            $this->errors['save'] = strtolower($result);
            return false;
        }
    }

    /**
     * Deletes the key for the given command
     *
     * @return bool
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function delete()
    {
        $result = $this->connector->doCommand($this->command . ' -r ' . $this->getId());
        if (strstr(strtolower($result), 'successfully removed')) {
            return true;
        }

        return false;
    }

    /**
     * Shows information about the object. Return plain string
     * todo add implementation for every type of entity for return array
     *
     * @return string
     */
    public function show()
    {
        $result = $this->connector->doCommand($this->command . ' -s ' . $this->getId());

        return $result;
    }

    /**
     * Update information about the object
     * @return string
     */
    public function update()
    {
        $result = $this->connector->doCommand($this->command . ' -u ' . $this->getId());

        return $result;
    }

    /**
     * Flashes the table of the object
     * @return string
     */
    public function flush()
    {
        $result = $this->connector->doCommand($this->command . ' -f');

        return $result;
    }

    /**
     * Check is set all required value
     * @return bool
     */
    protected function checkRequiredAttribute()
    {
        foreach ($this->requiredAttributes as $attribute){
            if (!(isset($this->attributes[$attribute]) && array_key_exists($attribute, $this->attributes))){
                $this->errors[$attribute] = 'Required';
            }
        }
        return count($this->errors) == 0;
    }

    /**
     * Get Id of entity
     * @return mixed
     */
    abstract public function getId();

    /**
     * Set Id of entity
     * @param string $id Identity of entity
     * @return mixed
     */
    abstract public function setId($id);

}