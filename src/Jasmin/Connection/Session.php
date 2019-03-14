<?php

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Exception\ConnectorException;

class Session
{
    /**
     * @var SocketConnection
     */
    private $connection;

    /**
     * Name of profile for persist and load commands
     *
     * @var string
     */
    protected $profile;

    /**
     * Session constructor.
     *
     * @param SocketConnection $connection
     *
     * @param string $profile
     */
    private function __construct(SocketConnection $connection, string $profile)
    {
        $this->connection = $connection;
        $this->profile = $profile;
    }

    /**
     * @param string $username
     *
     * @param string $password
     *
     * @param SocketConnection $connection
     *
     * @param string $profile
     *
     * @return Session
     *
     * @throws \Exception
     */
    public static function init(
        string $username,
        string $password,
        SocketConnection $connection,
        string $profile = 'jcli-prod'
    ): Session {
        //TODO Add more safety validation
        $username = trim($username);
        $password = trim($password);

        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Not set username or password');
        }

        $connection->write($username . PHP_EOL);
        $connection->write($password . PHP_EOL);

        $result = $connection->read();

        if (false !== strpos($result, 'Incorrect')) {
            throw new \InvalidArgumentException('Incorrect Username/Password');
        }

        return new self($connection, $profile);
    }

    /**
     * @param string $command
     *
     * @param bool $needWaitBeforeRead
     *
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

    /**
     * Writes $length bites and remove trash from socket output
     *
     * @param string $string
     *
     * @param int $length
     *
     * @return string
     */
    protected function normalize(string $string, int $length): string
    {
        return substr(str_replace('jcli :', '', trim($string)), $length);
    }

    /**
     * Persist command
     *
     * @throws ConnectorException
     */
    public function persist(): void
    {
        $this->runCommand('persist ' . $this->profile);
    }

    /**
     * Load command
     *
     * @throws ConnectorException
     */
    public function load(): void
    {
        $this->runCommand('load ' . $this->profile);
    }
}