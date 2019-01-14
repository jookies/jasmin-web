<?php

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Command\Group\Group;
use JasminWeb\Test\BaseTest;

class GroupCommandTest extends BaseTest
{
    private $session;
    protected $gid = 5;

    protected function setUp()
    {
        if (!$this->session) {
            $this->session = $this->getSession();
        }
    }

    public function testAddGroup()
    {
        $group = new Group($this->session);
        $errorStr = '';
        $this->assertTrue($group->add(['gid' => $this->gid], $errorStr), $errorStr);
    }

    /**
     * @depends testAddGroup
     */
    public function testAllGroup()
    {
        $group = new Group($this->session);
        $list = $group->all();
        $this->assertNotEmpty($list);
        $row = array_shift($list);
        $this->assertArrayHasKey('gid', $row);
    }

    /**
     * @depends testAddGroup
     */
    public function testDisableGroup()
    {
        $group = new Group($this->session);
        $this->assertTrue($group->disable($this->gid));
        $groups = $group->all();
        $this->assertStringContainsString( '!', $groups[0]['gid']);
    }

    /**
     * @depends testDisableGroup
     */
    public function testEnableGroup()
    {
        $group = new Group($this->session);
        $this->assertTrue($group->enable($this->gid));
        $groups = $group->all();
        $this->assertStringNotContainsString( '!', $groups[0]['gid']);
    }

    /**
     * @depends testEnableGroup
     */
    public function testAllAfterAddGroup()
    {
        $group = new Group($this->session);
        $this->assertCount(1, $group->all());
    }

    /**
     * @depends testAllAfterAddGroup
     */
    public function testRemoveGroup()
    {
        $group = new Group($this->session);
        $this->assertTrue($group->remove($this->gid));
        $this->assertCount(0, $group->all());
    }
}
