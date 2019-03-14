<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command\MtRouter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\InternalAddValidator;

class MtRouterBaseValidator extends InternalAddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['type', 'order', 'rate'];
    }

    /**
     * Find validator by data
     *
     * @param array $data
     *
     * @return AddValidator|null
     */
    protected function resolveValidator(array $data): ?AddValidator
    {
        switch ($data['type']) {
            case MtRouter::STATIC:
                return new StaticMtRouteValidator();
            case MtRouter::DEFAULT:
                return new DefaultMtRouteValidator();
            default:
                return null;
        }
    }

    /**
     * Add specific error if validator isn't found
     *
     * @param array $data
     */
    protected function addResolveError(array $data): void
    {
        $this->errors['type'] = 'Unknown MtRoute type ' . $data['type'];
    }
}