<?php

namespace JasminWeb\Test\Connection;

use JasminWeb\Exception\ConnectionException;
use JasminWeb\Jasmin\Connection\SocketConnection;
use JasminWeb\Test\BaseTest;

class SocketConnectionTest extends BaseTest
{
    /**
     * @return array
     */
    public function initWrongParametersProvider(): array
    {
        return [
            [null, null],
            [-1, -1],
            ['host', 'port'],
        ];
    }

    /**
     * @dataProvider initWrongParametersProvider
     * @param $host
     * @param $port
     * @throws ConnectionException
     */
    public function testInitWithWrongHostAndPort($host, $port)
    {
        $this->expectException(ConnectionException::class);
        SocketConnection::init((string) $host, (int) $port);
    }

    public function testSuccessInit()
    {
        $host = $this->getHost();
        $port = $this->getPort();

        $this->assertNotNull(SocketConnection::init($host, $port));
    }
}
