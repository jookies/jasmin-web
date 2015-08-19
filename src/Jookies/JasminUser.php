<?php
namespace Jookies;

/**
 * Class JasminUser
 *
 * id property is the uid for that class
 */
class JasminUser extends JasminObject
{
    var $command = 'user';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['uid'] = $id;
    }
}