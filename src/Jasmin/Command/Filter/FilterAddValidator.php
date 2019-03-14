<?php

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\InternalAddValidator;

class FilterAddValidator extends InternalAddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['fid', 'type'];
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveValidator(array $data): ?AddValidator
    {
        $validator = null;
        switch (strtolower($data['type'])) {
            case Filter::USER:
                $validator = new UserFilterAddValidator();
                break;
            case Filter::TRANSPARENT:
                $validator = new class extends AddValidator {
                    /**
                     * @return array
                     */
                    public function getRequiredAttributes(): array
                    {
                        return [];
                    }
                };
                break;
        }

        return $validator;
    }

    /**
     * {@inheritdoc}
     */
    protected function addResolveError(array $data): void
    {
        $this->errors['type'] = 'Unknown type of filter ' . $data['type'];
    }
}