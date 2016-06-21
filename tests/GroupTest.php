<?php

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;

class GroupTest extends PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $connection = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\Group($connection);
        $this->assertInternalType('array', $manager->getAll());
    }

    public function testAddNewOne()
    {
        $connection = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\Group($connection);
        $this->assertFalse($manager->checkExist('test_new_one'));
        $manager->setId('test_new_one');
        $this->assertTrue($manager->save());
        $this->assertTrue($manager->checkExist('test_new_one'));
        $this->assertTrue($manager->delete());
        $this->assertFalse($manager->checkExist('test_new_one'));
    }

    // todo add fictures for check group
    public function testCheckExistence()
    {
        $connection = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\Group($connection);
        $this->assertTrue($manager->checkExist('test_exist'));
        $this->assertFalse($manager->checkExist('test_not_exist'));
    }
}
