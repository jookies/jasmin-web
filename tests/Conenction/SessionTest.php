<?php

namespace JasminWeb\Test\Connection;

use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;

class SessionTest extends BaseTest
{
    public function testInitWrongUserAndPassword()
    {
        $connection = $this->getConnection();
        $this->expectException(\InvalidArgumentException::class);
        Session::init('test', 'test', $connection);
    }

    public function testSuccessInit()
    {
        $connection = $this->getConnection();
        $this->assertNotNull(Session::init($this->getPassword(), $this->getUsername(), $connection));
    }
}
