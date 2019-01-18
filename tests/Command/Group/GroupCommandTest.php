<?php

namespace JasminWeb\Test\Command\Group;

use JasminWeb\Jasmin\Command\Group\Group;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class GroupCommandTest extends BaseTest
{
    /**
     * @var MockObject|Session
     */
    private $session;

    /**
     * @var string
     */
    protected $gid = 'jTestG1';

    /**
     * {@inheritdoc}
     *
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function setUp()
    {
        if (!$this->session && $this->isRealJasminServer()) {
            $this->session = $this->getSession();
        } else {
            $this->session = $this->getSessionMock();
        }
    }

    /**
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testEmptyList()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
Total Groups: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $group = new Group($this->session);
        $list = $group->all();
        $this->assertEmpty($list);
    }

    /**
     * @depends testEmptyList
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testAddGroup()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $group = new Group($this->session);
        $errorStr = '';
        $this->assertTrue($group->add(['gid' => $this->gid], $errorStr), $errorStr);
    }

    /**
     * @depends testAddGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testListAfterAdd()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
#$this->gid
Total Groups: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $group = new Group($this->session);
        $list = $group->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('gid', $row);
        $this->assertEquals($this->gid, $row['gid']);
    }

    /**
     * @depends testListAfterAdd
     */
    public function testDisableGroup()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully disabled');
        }

        $group = new Group($this->session);
        $this->assertTrue($group->disable($this->gid));
    }

    /**
     * @depends testDisableGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testIsDisabledGroup()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
#!$this->gid
Total Groups: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $group = new Group($this->session);
        $groups = $group->all();
        $this->assertStringContainsString('!', $groups[0]['gid']);
    }

    /**
     * @depends testIsDisabledGroup
     */
    public function testEnableGroup()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully enabled');
        }

        $group = new Group($this->session);
        $this->assertTrue($group->enable($this->gid));
    }

    /**
     * @depends testEnableGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testIsEnabledGroup()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
#$this->gid
Total Groups: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $group = new Group($this->session);
        $groups = $group->all();
        $this->assertStringNotContainsString('!', $groups[0]['gid']);
    }

    /**
     * @depends testIsEnabledGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testRemoveGroup()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully removed');
        }

        $group = new Group($this->session);
        $this->assertTrue($group->remove($this->gid));
        $this->assertCount(0, $group->all());

        $this->testEmptyList();
    }
}
