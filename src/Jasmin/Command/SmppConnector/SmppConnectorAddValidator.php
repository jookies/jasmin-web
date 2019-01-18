<?php

namespace JasminWeb\Jasmin\Command\SmppConnector;

use JasminWeb\Jasmin\Command\AddValidator;

class SmppConnectorAddValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['cid'];
    }
}