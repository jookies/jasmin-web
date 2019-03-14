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

        $connection->write($username . PHP_EOL);
        $connection->write($password . PHP_EOL);
//        $connection->write(chr(0));

        $result = $connection->read();
        if (false !== strpos($result, 'Incorrect')) {
            throw new \InvalidArgumentException('Incorrect Username/Password');
        }

        return new self($connection);
    }

    /**
     * @param string $command
     * @param bool $needWaitBeforeRead
     * @return bool|string
     *
     * @throws ConnectorException
     */
    public function runCommand(string $command, bool $needWaitBeforeRead = false)
    {
        if (!$this->connection->isAlive()) {
            throw new ConnectorException('Try execute command without open socket');
        }

        $command = trim($command) . PHP_EOL;

        $this->connection->write($command);

        if ($needWaitBeforeRead) {
            $this->connection->wait();
        }

        return $this->normalize($this->connection->read(), strlen($command));
    }

    protected function normalize(string $string, int $length): string
    {
        return substr(str_replace('jcli :', '', trim($string)), $length);
    }

    public function persist(string $profile = 'jcli-prod')
    {
        $this->runCommand('persist ' . $profile);
    }

    public function load(string $profile = 'jcli-prod')
    {
        $this->runCommand('load ' . $profile);
    }
}