<?php namespace JasminWeb\Jasmin;

/**
 * Class JasminObject
 *
 * Provides an entry point for all jasmin cli modules.
 *
 */
abstract class BaseObject
{
    /**
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

    public function __construct(TelnetConnector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Get an array containing all the requested entities
     *
     * @return array
     */
    public function getAll()
    {
        return $this->connector->doCommand($this->command . ' -l');
    }

    /**
     * Saves the attributes for the given command (user, group, smppc, etc.)
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
        if (!$this->checkRequiredAttribute()) {
            return false;
        }
        $this->connector->doCommand($this->command . ' -a');

        foreach ($this->attributes as $property_key => $property_value) {
            $this->connector->doCommand($property_key . ' ' . $property_value);
        }
        $result = $this->connector->doCommand('ok');

        return !!strstr(strtolower($result), 'successfully added');
    }

    /**
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
        $result = $this->connector->doCommand($this->command . ' -r ' . $this->getId());
        if (strstr(strtolower($result), 'successfully removed')) {
            return true;
        }

        return false;
    }

    /**
     * Shows information about the object
     *
     * @return null|string
     */
    public function show()
    {
        $result = $this->connector->doCommand($this->command . ' -s ' . $this->getId());

        return $result;
    }

    /**
     * Update information about the object
     * @return null|string
     */
    public function update()
    {
        $result = $this->connector->doCommand($this->command . ' -u ' . $this->getId());

        return $result;
    }

    /**
     * Flashes the table of the object
     * @return null|string
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