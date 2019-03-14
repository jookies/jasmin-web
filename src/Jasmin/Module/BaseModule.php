<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Module;

use JasminWeb\Jasmin\Command\CommandInterface;
use JasminWeb\Jasmin\Connection\SessionInterface;
use JasminWeb\Jasmin\Response\Response;

abstract class BaseModule implements ModuleInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    protected function execute(string $commandClass, array $arguments): Response
    {

    }
}