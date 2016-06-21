<?php

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $connection = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\User($connection);
        $this->assertInternalType('array', $manager->getAll());
    }

    public function testCheckExistence()
    {
        $connection = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\User($connection);
        $this->assertTrue($manager->checkExist('test_exist'));
        $this->assertFalse($manager->checkExist('test_not_exist'));
    }


    public function testAddNewOne()
    {
        $connection = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\User($connection);
        $this->assertFalse($manager->checkExist('test_new_one'));
        $manager->setId('test_new_one');
        $manager->attributes['username'] = 'test_new_one';
        $manager->attributes['password'] = '12345';
        $manager->attributes['gid'] = 'test_new_one';

        $this->assertTrue($manager->add());
        $this->assertTrue($manager->checkExist('test_new_one'));
        $this->assertTrue($manager->delete());
        $this->assertFalse($manager->checkExist('test_new_one'));

        $groupManager = new \JasminWeb\Jasmin\Group($connection);
        $groupManager->setId('test_new_one');
        $groupManager->delete();
    }
}
