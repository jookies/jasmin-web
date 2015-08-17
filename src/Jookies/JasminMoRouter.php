<?php
namespace Jookies;

/**
 * Class JasminMoRouter
 *
 * id property is the order for that class
 */
class JasminMoRouter extends JasminObject
{
    var $id;
    var $command = 'morouter';
    var $properties;

    public function __construct()
    {
        $this->properties['order'] = $this->id;
        parent::__construct();
    }

}