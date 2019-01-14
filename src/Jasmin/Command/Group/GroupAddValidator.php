<?php

namespace JasminWeb\Jasmin\Command\Group;

use JasminWeb\Jasmin\Command\AddValidator;

class GroupAddValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['gid'];
    }
}