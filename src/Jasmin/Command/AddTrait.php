<?php

namespace JasminWeb\Jasmin\Command;

trait AddTrait
{
    /**
     * @param array $data
     * @param string $errorStr
     * @return bool
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function add(array $data, string &$errorStr = ''): bool
    {
        $validator = $this->getAddValidator();
        if (!$validator->checkRequiredAttributes($data)) {
            $errorStr = json_encode($validator->getErrors());
            return false;
        }
        $this->session->runCommand($this->getName() . ' -a');

        foreach ($data as $property_key => $property_value) {
            $this->session->runCommand($property_key . ' ' . $property_value);
        }

        $result = $this->session->runCommand('ok');
        if (false !== stripos($result, 'successfully')) {
            return true;
        }

        $errorStr = strtolower($result);
        return false;
    }

    /**
     * @return AddValidator
     */
    abstract protected function getAddValidator(): AddValidator;

    /**
     * @return bool
     */
    protected function isHeavy(): bool
    {
        return false;
    }
}