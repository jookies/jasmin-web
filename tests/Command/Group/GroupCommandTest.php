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
     * @var Group
     */
    protected $group;

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

        $this->group = new Group($this->session);
    }

    public function testEmptyList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
Total Groups: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $this->assertEmpty($this->group->all());
    }

    /**
     * @depends testEmptyList
     */
    public function testAddGroup(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        $errorStr = '';
        $this->assertTrue($this->group->add(['gid' => $this->gid], $errorStr), $errorStr);
    }

    /**
     * @depends testAddGroup
     */
    public function testListAfterAdd(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
#$this->gid
Total Groups: 1
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->group->all();
        $this->assertCount(1, $list);
        $row = array_shift($list);
        $this->assertArrayHasKey('gid', $row);
        $this->assertEquals($this->gid, $row['gid']);
    }

    /**
     * @depends testListAfterAdd
     */
    public function testDisableGroup(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully disabled');
        }

        $this->assertTrue($this->group->disable($this->gid));
    }

    /**
     * @depends testDisableGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testIsDisabledGroup(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
#!$this->gid
Total Groups: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $groups = $this->group->all();
        $this->assertStringContainsString('!', $groups[0]['gid']);
    }

    /**
     * @depends testIsDisabledGroup
     */
    public function testEnableGroup(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully enabled');
        }

        $this->assertTrue($this->group->enable($this->gid));
    }

    /**
     * @depends testEnableGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testIsEnabledGroup(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Group id
#$this->gid
Total Groups: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $groups = $this->group->all();
        $this->assertStringNotContainsString('!', $groups[0]['gid']);
    }

    /**
     * @depends testIsEnabledGroup
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testRemoveGroup(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully removed');
        }

        $this->assertTrue($this->group->remove($this->gid));

        $this->testEmptyList();
    }
}
