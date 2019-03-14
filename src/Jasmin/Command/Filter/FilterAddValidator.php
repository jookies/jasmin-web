<?php

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;

class FilterAddValidator extends AddValidator
{
    /**
     * @return array
     */
    public function getRequiredAttributes(): array
    {
        return ['fid', 'type'];
    }

    public function checkRequiredAttributes(array $data): bool
    {
        if (!parent::checkRequiredAttributes($data)) {
            return false;
        }

        if (!$validator = $this->getInternalValidator($data['type'])) {
            $this->errors['type'] = 'Unknown type';
            return false;
        }

        if (!$validator->checkRequiredAttributes($data)) {
            $this->errors = $validator->getErrors();
        }

        return empty($this->errors);
    }

    /**
     * @param string $type
     * @return AddValidator|null
     */
    private function getInternalValidator(string $type)
    {
        $validator = null;
        switch (strtolower($type)) {
            case 'userfilter':
                $validator = new UserFilterAddValidator();
                break;
        }

        return $validator;
    }
}