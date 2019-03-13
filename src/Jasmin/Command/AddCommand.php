<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command;

abstract class AddCommand extends Command
{
    final public function getFlag(): string
    {
        return '-a';
    }

    abstract public function validate(): bool;
}