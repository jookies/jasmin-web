<?php

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Exception\ConnectorException;

class Session
{
    /**
     * @var SocketConnection
     */
    private $connection;

    private function __construct(SocketConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $username
     * @param string $password
     * @param SocketConnection $connection
     *
     * @return Session
     */
    public static function init(string $username, string $password, SocketConnection $connection): Session
    {
        //TODO Add more safety validation
        $username = trim($username);
        $password = trim($password);
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Not set username or password');
        }

        $connection->write("$username\r");
        $connection->write("$password\r");
        $connection->write(chr(0));

        $result = $connection->read();
        if (false !== strpos($result, 'Incorrect')) {
            throw new \InvalidArgumentException('Incorrect Username/Password');
        }

        return new self($connection);
    }

    /**
     * @param string $command
     * @return bool|string
     *
     * @throws ConnectorException
     */
    public function runCommand(string $command)
    {
        if (!$this->connection->isAlive()) {
            throw new ConnectorException('Try execute command without open socket');
        }

        $command = trim($command) . "\r";

        $this->connection->write($command, false);
        $this->connection->write( ' ');

        return $this->connection->read();
    }

    /**
     * @return bool|string
     * @throws ConnectorException
     */
    public function persist()
    {
        return $this->runCommand('persist');
    }
}