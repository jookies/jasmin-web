<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command;

use JasminWeb\Jasmin\Response\Response;

abstract class ListCommand extends Command
{
    /**
     * @return string
     */
    final public function getFlag(): string
    {
        return '-l';
    }

    final public function parseResponse(string $data): Response
    {
        $exploded = explode('#', $data);
        unset($exploded[0], $exploded[1]);

        $response = new Response();

        return $response->setData($this->parseResponseAfterParent($exploded));
    }

    final public function validate(): bool
    {
        return true;
    }

    abstract protected function parseResponseAfterParent(array $data): array;
}