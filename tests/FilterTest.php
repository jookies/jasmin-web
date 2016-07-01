<?php

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;
use JasminWeb\Jasmin\Filter\Filter;


class FilterTest extends PHPUnit_Framework_TestCase
{
    protected static $telnetConnector;

    public static function setUpBeforeClass()
    {
        self::$telnetConnector = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\Filter\Filter(self::$telnetConnector);
        $manager->setId('test_new_one_f');
        $manager->delete();
        $manager->setId('test_not_exist');
        $manager->delete();


        $userManager = new \JasminWeb\Jasmin\User(self::$telnetConnector);
        $userManager->setId('test_new_one');
        $userManager->attributes['username'] = 'test_new_one';
        $userManager->attributes['password'] = '12345';
        $userManager->attributes['gid'] = 'test_new_one';
        $userManager->add();

        $connecorManager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $connecorManager->setId('test_new_one');
        $connecorManager->save();
    }

    public static function tearDownAfterClass()
    {
        $manager = new \JasminWeb\Jasmin\Filter\Filter(self::$telnetConnector);
        $manager->setId('test_new_one_f');
        $manager->delete();
        $manager->setId('test_not_exist');
        $manager->delete();
        $manager->setId('test_exist');
        $manager->delete();
        $manager->setId('fail_one_f');
        $manager->delete();

        $userManager = new \JasminWeb\Jasmin\User(self::$telnetConnector);
        $userManager->setId('test_new_one');
        $userManager->delete();

        $groupManager = new \JasminWeb\Jasmin\Group(self::$telnetConnector);
        $groupManager->setId('test_new_one');
        $groupManager->delete();

        $connecorManager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $connecorManager->setId('test_new_one');
        $connecorManager->delete();
    }

    public function testGetAll()
    {
        $manager = new \JasminWeb\Jasmin\Filter\Filter(self::$telnetConnector);
        $this->assertInternalType('array', $manager->getAll());
    }

    public function testCheckExistence()
    {
        $manager = new \JasminWeb\Jasmin\Filter\Filter(self::$telnetConnector);
        $this->assertFalse($manager->checkExist('test_not_exist'));
    }

    public function testAddUserFilter()
    {
        $manager = Filter::getFilter(Filter::UserFilter, self::$telnetConnector);
        $this->assertFalse($manager->checkExist('test_new_one_f'));
        $manager->setId('test_new_one_f');
        $manager->attributes['uid'] = 'test_new_one';
        $this->assertTrue($manager->add());
        $this->assertTrue($manager->checkExist('test_new_one_f'));
    }


    public function testAddUserFilterWithoutUser()
    {
        $manager = Filter::getFilter(Filter::UserFilter, self::$telnetConnector);
        $this->assertFalse($manager->checkExist('test_new_one_f'));
        $manager->setId('test_new_one_f');
        $manager->attributes['uid'] = 'test_fail_one';

        $this->assertFalse($manager->add());
        $this->assertFalse($manager->checkExist('test_new_one_f'));
    }


    public function testAddConnectionFilter()
    {
        $manager = Filter::getFilter(Filter::ConnectorFilter, self::$telnetConnector);
        $this->assertFalse($manager->checkExist('test_new_one_f'));
        $manager->setId('test_new_one_f');
        $manager->setCId('test_new_one');
        $this->assertTrue($manager->add());
        $this->assertTrue($manager->checkExist('test_new_one_f'));
    }


    public function testAddConnectionFilterWithoutUser()
    {
        $manager = Filter::getFilter(Filter::ConnectorFilter, self::$telnetConnector);
        $this->assertFalse($manager->checkExist('test_new_one_f'));
        $manager->setId('test_new_one_f');
        $manager->setCId('test_fail_one');

        $this->assertFalse($manager->add());
        $this->assertFalse($manager->checkExist('test_new_one_f'));
    }
}
