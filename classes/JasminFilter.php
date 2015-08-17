<?php
/**
 * Class JasminFilter
 *
 * id property is the fid for that class
 */
class JasminFilter extends JasminObject
{
    var $id;
    var $command = 'filter';
    var $properties;

    public function __construct()
    {
        $this->properties['fid'] = $this->id;
        parent::__construct();
    }

}