<?php

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Command\Group\Group;
use JasminWeb\Jasmin\Command\User\User;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;

class UserCommandTest extends BaseTest
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    protected $uid = 5;

    /**
     * @var int
     */
    protected $gid = 5;

    /**
     * @var string
     */
    protected $username = 'test_user';

    /**
     * @var string
     */
    protected $password = 'pass78';

    /**
     * {@inheritdoc}
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function setUp()
    {
        if (!$this->session) {
            $this->session = $this->getSession();
        }

        if (!$this->user) {
            $this->user = new User($this->session);
        }
    }

    /**
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function testAddUserWithoutGroupInDb()
    {
        (new Group($this->session))->remove($this->gid);
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
        (new Group($this->session))->add(['gid' => $this->gid]);
        $errstr = '';
        $this->assertTrue($this->user->add([
            'uid' => $this->uid,
            'gid' => $this->gid,
            'username' => $this->username,
            'password' => $this->password,
        ], $errstr), $errstr);
    }

    /**
     * @depends testAddUserWithGroup
     */
    public function testUserList()
    {
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
    }

    /**
     * @depends testUserList
     */
    public function testDisableUser()
    {
        $user = $this->user;
        $this->assertTrue($user->disable($this->uid));
        $list = $user->all();
        $this->assertStringContainsString('!', $list[0]['uid']);
    }

    /**
     * @depends testDisableUser
     */
    public function testEnableUser()
    {
        $user = $this->user;
        $this->assertTrue($user->enable($this->uid));
        $list = $user->all();
        $this->assertStringNotContainsString('!', $list[0]['uid']);
    }
}
