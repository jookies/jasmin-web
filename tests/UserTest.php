<?php

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */
class UserTest extends PHPUnit_Framework_TestCase
{
    protected static $telnetConnector;

    public static function setUpBeforeClass()
    {
        $adminUsername = getenv('jasmin_admin_username') ?: 'jcliadmin';
        $adminPassword = getenv('jasmin_admin_password') ?: 'jclipwd';
        $adminHost = getenv('jasmin_admin_host') ?: '127.0.0.1';
        $adminPort = getenv('jasmin_admin_port') ?: 8990;
        self::$telnetConnector = JasminConnector::init($adminUsername, $adminPassword, $adminHost, $adminPort);

        $groupManager = new \JasminWeb\Jasmin\Group(self::$telnetConnector);
        $groupManager->setId('test_new_one');
        $groupManager->delete();
        $groupManager->setId('test_exist');
        $groupManager->save();

        $userManager = new \JasminWeb\Jasmin\User(self::$telnetConnector);
        $userManager->setId('test_not_exist');
        $userManager->delete();
        $userManager->setId('test_new_one');
        $userManager->delete();
        $userManager->setId('test_exist');
        $userManager->delete();

        $userManager->setId('test_exist');
        $userManager->attributes['username'] = 'test_exist';
        $userManager->attributes['password'] = '12345';
        $userManager->attributes['gid'] = 'test_exist';
        $userManager->add();
    }

    public static function tearDownAfterClass()
    {
        $groupManager = new \JasminWeb\Jasmin\Group(self::$telnetConnector);
        $groupManager->setId('test_new_one');
        $groupManager->delete();
        $groupManager->setId('test_exist');
        $groupManager->delete();


        $groupManager->setId('test_not_exist');
        $groupManager->delete();
    }

    public function testGetAll()
    {
        $manager = new \JasminWeb\Jasmin\User(self::$telnetConnector);
        $this->assertInternalType('array', $manager->getAll());
    }

    public function testCheckExistence()
    {
        $manager = new \JasminWeb\Jasmin\User(self::$telnetConnector);
        $this->assertTrue($manager->checkExist('test_exist'));
        $this->assertFalse($manager->checkExist('test_not_exist'));
    }


    public function testAddNewOne()
    {
        $manager = new \JasminWeb\Jasmin\User(self::$telnetConnector);
        $groupManager = new \JasminWeb\Jasmin\Group(self::$telnetConnector);
        $groupManager->setId('test_new_one');
        $this->assertFalse($manager->checkExist('test_new_one'));
        $manager->setId('test_new_one');
        $manager->attributes['username'] = 'test_new_one';
        $manager->attributes['password'] = '12345';
        $manager->attributes['gid'] = 'test_new_one';

        $this->assertTrue($manager->add());
        $this->assertTrue($manager->checkExist('test_new_one'));
        $this->assertTrue($groupManager->checkExist('test_new_one'));
        $this->assertTrue($manager->delete());
        $this->assertFalse($manager->checkExist('test_new_one'));

        $this->assertTrue($groupManager->delete());
    }
}
