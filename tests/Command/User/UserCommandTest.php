<?php

namespace JasminWeb\Test\Command\User;

use JasminWeb\Jasmin\Command\Group\Group;
use JasminWeb\Jasmin\Command\User\User;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class UserCommandTest extends BaseTest
{
    /**
     * @var Session|MockObject
     */
    private $session;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var int
     */
    protected $uid = 'jTestU1';

    /**
     * @var int
     */
    protected $gid = 'jTestG1';

    /**
     * @var string
     */
    protected $username = 'jTestUN1';

    /**
     * @var string
     */
    protected $password = 'jTestPD1';

    /**
     * {@inheritdoc}
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function setUp()
    {
        if (!$this->session && $this->isRealJasminServer()) {
            $this->session = $this->getSession();
        } else {
            $this->session = $this->getSessionMock();
        }

        $this->user = new User($this->session);
    }

    /**
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testEmptyList()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#User id          Group id         Username         Balance MT SMS Throughput
Total Users: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->user->all();
        $this->assertEmpty($list);
    }

    /**
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testAddUserWithoutGroupInDb()
    {
        if (!$this->isRealJasminServer()) {
            $str = 'You must set User id (uid), group (gid), username and password before saving !';
            $this->session->method('runCommand')->willReturn($str);
        }

        $errstr = '';
        $this->assertFalse($this->user->add([
            'uid' => $this->uid,
            'gid' => $this->gid,
            'username' => $this->username,
            'password' => $this->password,
        ], $errstr), $errstr);
    }

    /**
     * @depends testAddUserWithoutGroupInDb
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testAddUserWithGroup()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        (new Group($this->session))->add(['gid' => $this->gid]);
        $errstr = '';
        $this->assertTrue($this->user->add([
            'uid' => $this->uid,
            'gid' => $this->gid,
            'username' => $this->username,
            'password' => $this->password,
        ], $errstr), $errstr);
        $this->assertTrue(true);
    }

    /**
     * @depends testAddUserWithGroup
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testUserList()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#User id          Group id         Username         Balance MT SMS Throughput
#$this->uid              $this->gid                $this->username              ND (!)  ND (!) ND/ND
Total Users: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->user->all();
        $this->assertCount(1, $list);

        $row = array_shift($list);

        $this->assertArrayHasKey('uid', $row);
        $this->assertArrayHasKey('gid', $row);
        $this->assertArrayHasKey('username', $row);
        $this->assertArrayHasKey('balance', $row);
        $this->assertArrayHasKey('mt', $row);
        $this->assertArrayHasKey('sms', $row);
        $this->assertArrayHasKey('throughput', $row);

        $this->assertEquals($this->gid, $row['gid']);
        $this->assertEquals($this->uid, $row['uid']);
        $this->assertEquals($this->username, $row['username']);
    }

    /**
     * @depends testUserList
     */
    public function testDisableUser()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully disabled');
        }

        $user = $this->user;
        $this->assertTrue($user->disable($this->uid));
    }

    /**
     * @depends testDisableUser
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testIsDisabledUser()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#User id          Group id         Username         Balance MT SMS Throughput
#!$this->uid              $this->gid                $this->username              ND (!)  ND (!) ND/ND
Total Users: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $users = $this->user->all();
        $this->assertStringContainsString('!', $users[0]['uid']);
    }

    /**
     * @depends testIsDisabledUser
     */
    public function testEnableUser()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully enabled');
        }

        $this->assertTrue($this->user->enable($this->uid));
    }

    /**
     * @depends testEnableUser
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testIsEnabledUser()
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#User id          Group id         Username         Balance MT SMS Throughput
#$this->uid              $this->gid                $this->username              ND (!)  ND (!) ND/ND
Total Users: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $users = $this->user->all();
        $this->assertStringNotContainsString('!', $users[0]['uid']);
    }

    /**
     * @depends testIsEnabledUser
     *
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testRemoveUser()
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully removed');
        }

        $this->assertTrue($this->user->remove($this->uid));

        $this->testEmptyList();
    }
}
