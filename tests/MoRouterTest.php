<?php

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 27.06.16
 */

use JasminWeb\Jasmin\TelnetConnector as JasminConnector;
use JasminWeb\Jasmin\Filter\Filter;
use JasminWeb\Jasmin\MoRouter\MoRouter;

class MoRouterTest extends PHPUnit_Framework_TestCase
{
    protected static $telnetConnector;

    public static function setUpBeforeClass()
    {
        $adminUsername = getenv('jasmin_admin_username') ?: 'jcliadmin';
        $adminPassword = getenv('jasmin_admin_password') ?: 'jclipwd';
        $adminHost = getenv('jasmin_admin_host') ?: '127.0.0.1';
        $adminPort = getenv('jasmin_admin_port') ?: 8990;
        self::$telnetConnector = JasminConnector::init($adminUsername, $adminPassword, $adminHost, $adminPort);

        $connectorManager = new \JasminWeb\Jasmin\Connector(self::$telnetConnector);
        $connectorManager->setId('test_new_one');
        $connectorManager->save();

        $filterManager = Filter::getFilter(Filter::ConnectorFilter, self::$telnetConnector);
        $filterManager->setId('test_new_one_f');
        $filterManager->setCId('test_new_one');
        $filterManager->add();
    }

    public static function tearDownAfterClass()
    {
        $manager = new MoRouter(self::$telnetConnector);
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
    }


    public function testAddStaticRouter()
    {
        $manager = MoRouter::getRouter(MoRouter::StaticMORoute, self::$telnetConnector);
        $manager->setId(100);
        $manager->setConnector('test_new_one');
        $manager->setFilters(['test_new_one_f']);
        $this->assertTrue($manager->add(), strtr('Can`t insert new morouter because of :error', [':error' => json_encode($manager->errors)]));
    }
}
