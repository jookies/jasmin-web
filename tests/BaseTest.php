<?php

namespace JasminWeb\Test;

use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Jasmin\Connection\SocketConnection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @return SocketConnection|MockObject
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function getConnection(): SocketConnection
    {
        return SocketConnection::init($this->getHost(), $this->getPort(), $this->getWaitTime());
    }

    /**
     * @return SocketConnection|MockObject
     */
    protected function getConnectionMock()
    {
        return $this->createMock(SocketConnection::class);
    }

    /**
     * @return Session|MockObject
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function getSession(): Session
    {
        return Session::init($this->getUsername(), $this->getPassword(), $this->getConnection());
    }

    /**
     * @return Session|MockObject
     */
    public function getSessionMock()
    {
        return $this->createMock(Session::class);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return getenv('jasmin_admin_username') ?: 'jcliadmin';
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return getenv('jasmin_admin_password') ?: 'jclipwd';
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return getenv('jasmin_admin_host') ?: '127.0.0.1';
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return getenv('jasmin_admin_port') ?: 8990;
    }

    public function isRealJasminServer(): bool
    {
        return getenv('jasmin_real_server') ? true : false;
    }

    public function getWaitTime(): int
    {
        return getenv('jasmin_read_write_wait_time') ?: 1000000;
    }
}
