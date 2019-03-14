<?php

namespace JasminWeb\Jasmin\Command;

use JasminWeb\Jasmin\Connection\Session;

abstract class BaseCommand
{
    use ListTrait, AddTrait, RemoveTrait;

    /**
     * @var Session
     */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    abstract protected function getName(): string;

    abstract protected function isHeavy(): bool;
}