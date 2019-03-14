<?php

namespace JasminWeb\Test\Command\MoRouter;

use JasminWeb\Jasmin\Command\Filter\Filter;
use JasminWeb\Jasmin\Command\HttpConnector\Connector;
use JasminWeb\Jasmin\Command\MoRouter\MoRouter;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class MoRouterCommandTest extends BaseTest
{
    /**
     * @var MoRouter
     */
    private $router;

    /**
     * @var Session|MockObject
     */
    protected $session;

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

        $this->router = new MoRouter($this->session);
    }

    public function testEmptyList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Order Type                    Connector ID(s)                  Filter(s)
Total MO Routes: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->router->all();
        $this->assertEmpty($list);
    }

    /**
     * @depends testEmptyList
     */
    public function testNotEmptyListWithFakeData(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Order Type                    Connector ID(s)                  Filter(s)
#30    FailoverMORoute         http(http_4), http(http_5)       <T>, <T>
#20    RandomRoundrobinMORoute http(http_2), http(http_3)       <T>, <T>
#15    StaticMORoute           smpps(user_1)                    <T>
#10    StaticMORoute           http(http_1)                     <T>
#0     DefaultRoute            http(http_default)
Total MO Routes: 5
STR;
            $this->session->method('runCommand')->willReturn($listStr);

            $list = $this->router->all();
            $this->assertCount(5, $list);
            foreach ($list as $row) {
                $this->assertArrayHasKey('order', $row);
                $this->assertInternalType('int', $row['order']);
                $this->assertArrayHasKey('type', $row);
                $this->assertInternalType('string', $row['type']);
                $this->assertArrayHasKey('connectors', $row);
                $this->assertInternalType('array', $row['connectors']);
                $this->assertArrayHasKey('filters', $row);
                $this->assertInternalType('array', $row['filters']);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testNotEmptyListWithFakeData
     */
    public function testAddDefaultRoute(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';

        (new Connector($this->session))->add([
            'url' => 'http://google.com',
            'method' => 'GET',
            'cid' => 'http-01'
        ]);

        $data = [
            'type' => 'defaultroute',
            'connector' => 'http(http-01)',
            'order' => 0
        ];

        $this->assertTrue($this->router->add($data, $errstr), $errstr);
    }

    /**
     * @depends testAddDefaultRoute
     */
    public function testRoutersListAfterAddDefaultRoute(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Order Type                    Connector ID(s)                  Filter(s)
#0     DefaultRoute            http(http-01)
Total MO Routes: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->router->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('order', $row);
        $this->assertInternalType('int', $row['order']);
        $this->assertArrayHasKey('type', $row);
        $this->assertInternalType('string', $row['type']);
        $this->assertArrayHasKey('connectors', $row);
        $this->assertInternalType('array', $row['connectors']);
        $this->assertArrayHasKey('filters', $row);
        $this->assertInternalType('array', $row['filters']);

        $this->testRemoveRoute(0);
    }

    /**
     * @depends testRoutersListAfterAddDefaultRoute
     *
     */
    public function testAddStaticRouter(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';

        (new Filter($this->session))->add([
            'type' => 'transparentfilter',
            'fid' => 'jFilter1',
        ]);

        (new Connector($this->session))->add([
            'url' => 'http://google.com',
            'method' => 'GET',
            'cid' => 'http-01'
        ]);

        $data = [
            'type' => 'staticmoroute',
            'connector' => 'http(http-01)',
            'order' => 10,
            'filters' => ['jFilter1']
        ];

        $this->assertTrue($this->router->add($data, $errstr), $errstr);
    }

    /**
     * @depends testAddStaticRouter
     */
    public function testRoutersListAfterAddStaticRoute(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Order Type                    Connector ID(s)                  Filter(s)
#10    StaticMORoute           http(http_1)                     <T>
Total MO Routes: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->router->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('order', $row);
        $this->assertInternalType('int', $row['order']);
        $this->assertArrayHasKey('type', $row);
        $this->assertInternalType('string', $row['type']);
        $this->assertArrayHasKey('connectors', $row);
        $this->assertInternalType('array', $row['connectors']);
        $this->assertArrayHasKey('filters', $row);
        $this->assertInternalType('array', $row['filters']);

        $this->testRemoveRoute(10);

        (new Filter($this->session))->remove('jFilter1');
    }

    /**
     * @param int $key
     */
    public function testRemoveRoute(?int $key = null): void
    {
        if (null === $key) {
            $this->assertTrue(true);
            return;
        }

        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->router->remove($key));
        $this->testEmptyList();
    }
}
