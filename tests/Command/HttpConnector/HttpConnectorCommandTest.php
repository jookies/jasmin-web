<?php

namespace JasminWeb\Test\Command\HttpConnector;

use JasminWeb\Jasmin\Command\HttpConnector\Connector;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class HttpConnectorCommandTest extends BaseTest
{
    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var Session|MockObject
     */
    protected $session;

    /**
     * @var string
     */
    protected $cid = 'jTestHttpC1';

    /**
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function setUp()
    {
        if (!$this->session && $this->isRealJasminServer()) {
            $this->session = $this->getSession();
        } else {
            $this->session = $this->getSessionMock();
        }

        $this->connector = new Connector($this->session);
    }

    /**
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testEmptyList()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Connector id                        Service Session          Starts Stops
Total connectors: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->connector->all();
        $this->assertEmpty($list);
    }

    /**
     * @depends testEmptyList
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testNotEmptyListWithFakeData()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Httpcc id        Type                   Method URL
#HTTP-01          HttpConnector          GET    http://10.10.20.125/receive-sms/mo.php
#HTTP-02          HttpConnector          POST    http://10.10.20.125/receive-sms/mo.php
Total Httpccs: 2
STR;
            $this->session->method('runCommand')->willReturn($listStr);

            $list = $this->connector->all();
            $this->assertCount(2, $list);
            foreach ($list as $row) {
                $this->assertArrayHasKey('cid', $row);
                $this->assertInternalType('string', $row['cid']);
                $this->assertArrayHasKey('type', $row);
                $this->assertInternalType('string', $row['type']);
                $this->assertArrayHasKey('method', $row);
                $this->assertInternalType('string', $row['method']);
                $this->assertArrayHasKey('url', $row);
                $this->assertInternalType('string', $row['url']);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testNotEmptyListWithFakeData
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testAddConnector()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';

        $data = [
            'cid' => $this->cid,
            'url' => 'http://10.10.20.125/receive-sms/mo.php',
            'method' => 'GET'
        ];

        $this->assertTrue($this->connector->add($data, $errstr), $errstr);
        $this->session->persist();
    }

    /**
     * @depends testAddConnector
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testConnectorsList()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Httpcc id        Type                   Method URL
#$this->cid          HttpConnector          POST    http://10.10.20.125/receive-sms/mo.php
Total Httpccs: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->connector->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('cid', $row);
        $this->assertInternalType('string', $row['cid']);
        $this->assertArrayHasKey('type', $row);
        $this->assertInternalType('string', $row['type']);
        $this->assertArrayHasKey('method', $row);
        $this->assertInternalType('string', $row['method']);
        $this->assertArrayHasKey('url', $row);
        $this->assertInternalType('string', $row['url']);
    }

    /**
     * @depends testConnectorsList
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testRemoveConnector()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->connector->remove($this->cid));
        $this->session->persist();
        $this->testEmptyList();
    }
}
