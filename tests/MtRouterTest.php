<?php

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 27.06.16
 */

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;
use JasminWeb\Jasmin\Filter\Filter;
use JasminWeb\Jasmin\MtRouter\MtRouter;

class MtRouterTest extends PHPUnit_Framework_TestCase
{
    protected static $telnetConnector;

    public static function setUpBeforeClass()
    {
        $adminUsername = getenv('jasmin_admin_username') ?: 'jcliadmin';
        $adminPassword = getenv('jasmin_admin_password') ?: 'jclipwd';
        $adminHost = getenv('jasmin_admin_host') ?: '127.0.0.1';
        $adminPort = getenv('jasmin_admin_password') ?: 8990;
        self::$telnetConnector = JasminConnector::init($adminUsername, $adminPassword, $adminHost, $adminPort);

        $connectorManager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $connectorManager->setId('test_new_one');
        $connectorManager->save();

        $filterManager = Filter::getFilter(Filter::ConnectorFilter, self::$telnetConnector);
        $filterManager->setId('test_new_one_f');
        $filterManager->setCId('test_new_one');
        $filterManager->add();

        $filterManager = Filter::getFilter(Filter::UserFilter, self::$telnetConnector);
        $filterManager->setId('test_new_u_f');
        $filterManager->attributes['uid'] = 'test_exist';
        $filterManager->add();
    }

    public static function tearDownAfterClass()
    {
        $manager = new MtRouter(self::$telnetConnector);
        $manager->setId(100);
        $manager->delete();
        $manager->setId(110);
        $manager->delete();

        $connectorManager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $connectorManager->setId('test_new_one');
        $connectorManager->delete();

        $manager = Filter::getFilter(Filter::ConnectorFilter, self::$telnetConnector);
        $manager->setId('test_new_one_f');
        $manager->delete();
        $manager->setId('test_new_u_f');
        $manager->delete();
    }


    public function testAddStaticRouter()
    {
        $manager = MtRouter::getRouter(MtRouter::StaticMTRoute, self::$telnetConnector);
        $manager->setId(101);
        $manager->attributes['rate'] = '100.0';
        $manager->setConnector('test_new_one');
        $manager->setFilters(['test_new_u_f']);
        $this->assertTrue($manager->add(), strtr('Can`t insert new MtRouter because of :error', [':error' => json_encode($manager->errors)]));
    }
}
