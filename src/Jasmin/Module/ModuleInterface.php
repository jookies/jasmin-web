<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Module;

use JasminWeb\Jasmin\Response\Response;

interface ModuleInterface
{
    public function add(array $data): Response;
    public function remove(string $key): Response;
    public function list(): Response;
}