<?php
namespace Jookies;

/**
 * Class JasminGroup
 *
 * id property is the gid for that class
 */
class JasminGroup extends JasminObject
{
    var $command = 'group';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['gid'] = $id;
    }
}