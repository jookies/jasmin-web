<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command\MtRouter;

use JasminWeb\Jasmin\Command\AddValidator;

class DefaultMtRouteValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['connector'];
    }
}