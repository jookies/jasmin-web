<?php

namespace JasminWeb\Test\Command\MtRouter;

use JasminWeb\Jasmin\Command\Filter\Filter;
use JasminWeb\Jasmin\Command\Group\Group;
use JasminWeb\Jasmin\Command\MtRouter\MtRouter;
use JasminWeb\Jasmin\Command\SmppConnector\Connector;
use JasminWeb\Jasmin\Command\User\User;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class MtRouterCommandTest extends BaseTest
{
    /**
     * @var MtRouter
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

        $this->router = new MtRouter($this->session);
    }

    public function testEmptyList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Order Type                    Rate    Connector ID(s)                     Filter(s)
Total MT Routes: 0
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
#Order Type                    Rate    Connector ID(s)                     Filter(s)
#20    FailoverMTRoute         0 (!)   smppc(smppcc_3), smppc(smppcc_4)    <T>
#20    RandomRoundrobinMTRoute 0 (!)   smppc(smppcc_2), smppc(smppcc_3)    <T>
#10    StaticMTRoute           0 (!)   smppc(smppcc_1)                     <T>, <T>
#0     DefaultRoute            0 (!)   smppc(smppcc_default)
STR;
            $this->session->method('runCommand')->willReturn($listStr);

            $list = $this->router->all();
            $this->assertCount(5, $list);
            foreach ($list as $row) {
                $this->assertArrayHasKey('order', $row);
                $this->assertInternalType('int', $row['order']);
                $this->assertArrayHasKey('type', $row);
                $this->assertInternalType('string', $row['type']);
                $this->assertArrayHasKey('rate', $row);
                $this->assertInternalType('float', $row['type']);
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
    public function testAddStaticRoute(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';

        $this->assertTrue((new Group($this->session))->add(['gid' => 'jG1']));

        $this->assertTrue((new User($this->session))->add([
            'gid' => 'jG1',
            'username' => 'jUser1',
            'password' => 'jPasssw1',
            'uid' => 'jUser1',
        ]));

        $this->assertTrue((new Connector($this->session))->add([
            'cid' => 'smpp-01'
        ]));

        $this->assertTrue((new Filter($this->session))->add([
            'fid' => 'jFilter1',
            'type' => Filter::USER,
            'uid' => 'jUser1'
        ]));

        $data = [
            'type' => MtRouter::STATIC,
            'filters' => ['jFilter1'],
            'order' => 99,
            'rate' => 0,
            'connector' => 'smppc(smpp-01)'
        ];

        $this->assertTrue($this->router->add($data, $errstr), $errstr);


    }

    /**
     * @depends testAddStaticRoute
     */
    public function testRoutersListAfterAddStaticRoute(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Order Type                    Rate    Connector ID(s)                     Filter(s)
#99    StaticMTRoute           0 (!)   smppc(smpp-01)                     <T>
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
        $this->assertArrayHasKey('rate', $row);
        $this->assertInternalType('float', $row['type']);
        $this->assertArrayHasKey('connectors', $row);
        $this->assertInternalType('array', $row['connectors']);
        $this->assertArrayHasKey('filters', $row);
        $this->assertInternalType('array', $row['filters']);

        $this->testRemoveRoute(0);

        $this->assertTrue((new Group($this->session))->remove('jG1'));
    }

    /**
     * @depends testRoutersListAfterAddStaticRoute
     */
    public function testAddDefaultRoute(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errstr = '';

        $this->assertTrue((new Connector($this->session))->add([
            'cid' => 'smpp-01'
        ]));

        $data = [
            'type' => MtRouter::DEFAULT,
            'order' => 10,
            'rate' => 0,
            'connector' => 'smppc(smpp-01)'
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
#Order Type                    Rate    Connector ID(s)                     Filter(s)
#0     DefaultRoute            0 (!)   smppc(smpp-01)
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
        $this->assertArrayHasKey('rate', $row);
        $this->assertInternalType('float', $row['type']);
        $this->assertArrayHasKey('connectors', $row);
        $this->assertInternalType('array', $row['connectors']);
        $this->assertArrayHasKey('filters', $row);
        $this->assertInternalType('array', $row['filters']);

        $this->testRemoveRoute(0);

        $this->assertTrue((new Connector($this->session))->remove('smpp-01'));
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
