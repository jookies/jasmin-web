<?php

namespace JasminWeb\Test;

use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Jasmin\Connection\SocketConnection;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @return SocketConnection
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function getConnection(): SocketConnection
    {
        return SocketConnection::init($this->getHost(), $this->getPort());
    }

    /**
     * @return Session
     * @throws \JasminWeb\Exception\ConnectionException
     */
    protected function getSession(): Session
    {
        return Session::init($this->getUsername(), $this->getPassword(), $this->getConnection());
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
}
