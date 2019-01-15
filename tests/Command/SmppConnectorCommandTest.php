<?php

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Command\SmppConnector\Connector;
use JasminWeb\Test\BaseTest;

class SmppConnectorCommandTest extends BaseTest
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
        $this->assertArrayHasKey('status', $row);
        $this->assertArrayHasKey('session', $row);
        $this->assertArrayHasKey('starts', $row);
        $this->assertArrayHasKey('stops', $row);
    }
}
