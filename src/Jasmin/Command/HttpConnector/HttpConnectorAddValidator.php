<?php

namespace JasminWeb\Jasmin\Command\HttpConnector;

use JasminWeb\Jasmin\Command\AddValidator;

class HttpConnectorAddValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['cid', 'url', 'method'];
    }
}