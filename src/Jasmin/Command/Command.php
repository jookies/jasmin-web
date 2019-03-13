<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command;

abstract class Command implements CommandInterface
{
    private $arguments = [];

    /**
     * {@inheritdoc}
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgument($index)
    {
        return $this->arguments[$index] ?? null;
    }

    public function isHeavy(): bool
    {
        return false;
    }

    public function getFlag(): string
    {
        return '';
    }

    public function isNeedPersist(): bool
    {
        return false;
    }
}