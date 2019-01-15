<?php

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Command\HttpConnector\Connector;
use JasminWeb\Test\BaseTest;

class HttpConnectorCommandTest extends BaseTest
{
    /**
     * @var Connector
     */
    private $connector;

    protected function setUp()
    {
        if (!$this->connector) {
            $this->connector = new Connector($this->getSession());
        }
    }

    public function testAllConnectors()
    {
        $list = $this->connector->all();
        $this->assertNotEmpty($list);
        $row = array_shift($list);
        $this->assertArrayHasKey('cid', $row);
        $this->assertArrayHasKey('type', $row);
        $this->assertArrayHasKey('method', $row);
        $this->assertArrayHasKey('url', $row);
    }
}
