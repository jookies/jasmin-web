<?php
namespace Jookies;

/**
 * Class JasminGroup
 *
 * Usage:
 * $groupcon = new JasminGroup();
 * $groupcon->properties['gid'] = 'customers';
 * $groupcon->save();
 *
 * or
 *
 * $groupcon->delete();
 */
class JasminGroup extends JasminObject
{
    var $id;
    var $command = 'group';
    var $properties;

    public function __construct()
    {
        $this->properties['gid'] = $this->id;
        parent::__construct();
    }

}