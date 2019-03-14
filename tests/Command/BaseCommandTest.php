<?php

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class BaseCommandTest extends BaseTest
{
    /**
     * @var Session|MockObject
     */
    protected $session;

    public function setUp()
    {
        if (!$this->session && $this->isRealJasminServer()) {
            $this->session = $this->getSession();
        } else {
            $this->session = $this->getSessionMock();
        }
    }
}