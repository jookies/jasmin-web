<?php
namespace Jookies;

class JasminUser extends JasminObject
{
    var $id;
    var $command = 'user';
    var $properties;

    public function __construct()
    {
        $this->properties['uid'] = $this->id;
        parent::__construct();
    }
}