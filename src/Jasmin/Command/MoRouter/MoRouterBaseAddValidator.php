<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command\MoRouter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\InternalAddValidator;

class MoRouterBaseAddValidator extends InternalAddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['type', 'order'];
    }

    protected function resolveValidator(array $data): ?AddValidator
    {
        switch (strtolower($data['type'])) {
            case 'defaultroute':
                return new DefaultMoRouteValidator();
            case 'staticmoroute':
                return new StaticMoRouteValidator();
            default:
                return null;
        }
    }

    protected function addResolveError(array $data): void
    {
        $this->errors[$data['type']] = 'Unknown type' . $data['type'];
    }
}