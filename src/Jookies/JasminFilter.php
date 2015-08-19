<?php
namespace Jookies;

/**
 * Class JasminFilter
 *
 * id property is the fid for that class
 */
class JasminFilter extends JasminObject
{
    var $command = 'filter';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }
    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['fid'] =$id;
    }

}