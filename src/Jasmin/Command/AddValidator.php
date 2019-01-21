<?php

namespace JasminWeb\Jasmin\Command;

abstract class AddValidator
{
    private $errors = [];

    /**
     * @return array
     */
    abstract public function getRequiredAttributes(): array;

    /**
     * @param array $data
     * @return bool
     */
    public function checkRequiredAttributes(array $data): bool
    {
        $required = $this->getRequiredAttributes();
        $diff = array_diff($required, array_keys($data));

        foreach ($diff as $item) {
            $this->errors[$item] = 'Required';
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}