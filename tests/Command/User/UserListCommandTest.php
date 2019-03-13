<?php

namespace JasminWeb\Test\Command\User;

use JasminWeb\Jasmin\Command\Group\GroupAddCommand;
use JasminWeb\Jasmin\Command\User\UserListCommand;
use JasminWeb\Jasmin\Connection\NewSession;
use JasminWeb\Test\BaseTest;

class UserListCommandTest extends BaseTest
{
    public function testEmptyList()
    {
        $connection = $this->getConnection();
        $session = NewSession::init($this->getUsername(), $this->getPassword(), $connection);

        $command = new UserListCommand();

        $result = $session->runCommand($command);

        $this->assertFalse($result->hasError());
        $this->assertEmpty($result->getData());

        $command = new GroupAddCommand();
        $command->setArguments(['gid' => 'JTestG1']);

        $result = $session->runCommand($command);
        $this->assertFalse($result->hasError());
    }
}
