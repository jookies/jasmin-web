<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Exception\ConnectorException;
use JasminWeb\Jasmin\Command\CommandInterface;
use JasminWeb\Jasmin\Response\Response;

class NewSession
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
     * @return self
     */
    public static function init(string $username, string $password, SocketConnection $connection): self
    {
        //TODO Add more safety validation
        $username = trim($username);
        $password = trim($password);
        if (empty($username) || empty($password)) {
            throw new \InvalidArgumentException('Not set username or password');
        }

        $connection->write("$username\r");
        $connection->write("$password\r\n");
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
     * @return array
     *
     * @throws ConnectorException
     */
    public function runCommand(CommandInterface $command): Response
    {
        if (!$this->connection->isAlive()) {
            throw new ConnectorException('Try execute command without open socket');
        }

        if (!$command->validate()) {
            throw new \RuntimeException('Validate error');
        }

        $arguments = $command->getArguments();

        $length = 0;

        $initCommand = $command->getId() . ' ' . $command->getFlag() . PHP_EOL;

        $length += strlen($initCommand);

        $this->connection->write($initCommand);

        foreach ($arguments as $key => $value) {
            $str = $key . ' ' . $value . PHP_EOL;
            $length += strlen($str);
            $this->connection->write($str);
        }

        if ($command->isHeavy()) {
            $this->connection->wait();
        }

        $normalizedData = $this->normalize($this->connection->read(), $length);

        return $command->parseResponse($normalizedData);
    }

    protected function normalize(string $string, int $length): string
    {
        return substr(str_replace('jcli :', '', trim($string)), $length);
    }
}