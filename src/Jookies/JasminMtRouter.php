<?php
namespace Jookies;

/**
 * Class JasminMtRouter
 *
 * id property is the order for that class
 */
class JasminMtRouter extends JasminObject
{
    var $command = 'mtrouter';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['order'] =$id;
    }
}