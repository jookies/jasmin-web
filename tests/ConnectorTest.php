<?php

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;

class ConnectorTest extends PHPUnit_Framework_TestCase
{
    protected static $telnetConnector;

    public static function setUpBeforeClass()
    {
        self::$telnetConnector = JasminConnector::init('jcliadmin', 'jclipwd');
        $manager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $manager->setId('test_new_one');
        $manager->delete();
        $manager->setId('test_not_exist');
        $manager->delete();
        $manager->setId('test_exist');
        $manager->save();
    }

    public static function tearDownAfterClass()
    {
        $manager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $manager->setId('test_new_one');
        $manager->delete();
        $manager->setId('test_not_exist');
        $manager->delete();
        $manager->setId('test_exist');
        $manager->delete();
    }

    public function testGetAll()
    {
        $manager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $this->assertInternalType('array', $manager->getAll());
    }

    public function testAddNewOne()
    {
        $manager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $this->assertFalse($manager->checkExist('test_new_one'));
        $manager->setId('test_new_one');
        $this->assertTrue($manager->save(), strtr('Save failed because of :reason', [':reason' => json_encode($manager->errors)]));
        $this->assertTrue($manager->checkExist('test_new_one'));
        $this->assertTrue($manager->delete());
        $this->assertFalse($manager->checkExist('test_new_one'));
    }

    public function testCheckExistence()
    {
        $manager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $this->assertTrue($manager->checkExist('test_exist'));
        $this->assertFalse($manager->checkExist('test_not_exist'));
    }
}
