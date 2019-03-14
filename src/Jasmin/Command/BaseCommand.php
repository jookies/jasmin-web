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

    /**
     * BaseCommand constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    abstract protected function getName(): string;

    /**
     * For heavy commands, if return true, connection will wait some period
     *
     * @return bool
     */
    protected function isHeavy(): bool
    {
        return false;
    }

    /**
     * If need execute persist command after this
     *
     * @return bool
     */
    protected function isNeedPersist(): bool
    {
        return false;
    }
}