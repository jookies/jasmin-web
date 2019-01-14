<?php

namespace JasminWeb\Jasmin\Command\User;

use JasminWeb\Jasmin\Command\AddValidator;

class UserAddValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['username', 'password', 'uid', 'gid'];
    }
}