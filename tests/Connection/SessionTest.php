<?php

namespace JasminWeb\Test\Connection;

use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;

class SessionTest extends BaseTest
{
    /**
     * @throws \JasminWeb\Exception\ConnectionException
     */
    public function testInitWrongUserAndPassword()
    {
        $connection = $this->getConnection();
        $this->expectException(\InvalidArgumentException::class);
        Session::init('test', 'test', $connection);
    }

    /**
     * @throws \JasminWeb\Exception\ConnectionException
     */
    public function testSuccessInit()
    {
        $session = $this->isRealJasminServer() ? $this->getSession() : $this->getSessionMock();
        $this->assertNotNull($session);
    }
}
