<?php
//include('autoload.php');
include('classes/JasminGroup.php');

class JcliTest extends PHPUnit_Framework_TestCase
{

    public function test_group_addition()
    {

        $groupconn = new JasminGroup();

        $groupconn->getAll();

        $groupconn->properties['gid'] = 'customers3';
        $this->assertEquals('Successfully added Group [' . $groupconn->properties['gid'] . ']', $groupconn->save());

        $groupconn->properties['gid'] = 'customers2';
        $this->assertEquals('Successfully added Group [' . $groupconn->properties['gid'] . ']', $groupconn->save());


        $groupconn->key = 'customers2';
        $this->assertEquals('Successfully removed Group id:' . $groupconn->key, $groupconn->delete());
    }

    // ...
}
