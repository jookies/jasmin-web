<?php

/**
 * Class JasminMtRouter
 *
 * id property is the order for that class
 */
class JasminMtRouter extends JasminObject
{
    var $id;
    var $command = 'mtrouter';
    var $properties;

    public function __construct()
    {
        $this->properties['order'] = $this->id;
        parent::__construct();
    }

}