<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Connection;

use JasminWeb\Jasmin\Command\CommandInterface;
use JasminWeb\Jasmin\Response\Response;

interface SessionInterface
{
    public function runCommand(CommandInterface $command): Response;
}