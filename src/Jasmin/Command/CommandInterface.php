<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command;

use JasminWeb\Jasmin\Response\Response;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getFlag(): string;

    /**
     * @return bool
     */
    public function isHeavy(): bool;

    /**
     * @return bool
     */
    public function isNeedPersist(): bool;

    /**
     * Sets the arguments for the command.
     *
     * @param array $arguments List of arguments.
     */
    public function setArguments(array $arguments);

    /**
     * Gets the arguments of the command.
     *
     * @return array
     */
    public function getArguments(): array;

    /**
     * Gets the argument of the command at the specified index.
     *
     * @param int $index Index of the desired argument.
     *
     * @return mixed|null
     */
    public function getArgument($index);

    /**
     * Parses a raw response and returns a PHP object.
     *
     * @param string $data Binary string containing the whole response.
     *
     * @return mixed
     */
    public function parseResponse(string $data): Response;

    /**
     * @return bool
     */
    public function validate(): bool;
}