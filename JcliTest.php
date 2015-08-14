<?php
//include('autoload.php');
include('classes/JasminGroup.php');

class JcliTest extends PHPUnit_Framework_TestCase
{

    public function test_group_addition()
    {

        $groupconn = new JasminGroup();

        //$groupconn->getgroups();

        $groupconn->gid = 'c1';

        // Assert
        //$this->assertEquals('> ok', $groupconn->save());

        $this->assertEquals('Successfully added Group [' . $groupconn->gid . ']', $groupconn->save());
    }

    // ...
}
