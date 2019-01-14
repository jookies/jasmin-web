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
        foreach ($data as $key => $value) {
            if (!in_array($key, $required, true)) {
                $this->errors[$key] = 'Required';
            }
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