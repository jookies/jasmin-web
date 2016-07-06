<?php
namespace Jookies;

/**
 * Class JasminMoRouter
 *
 * id property is the order for that class
 */
class JasminMoRouter extends JasminObject
{
    var $command = 'morouter';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['order'] = $id;
    }
}