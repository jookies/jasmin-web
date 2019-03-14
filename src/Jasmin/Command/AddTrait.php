<?php

namespace JasminWeb\Jasmin\Command;

trait AddTrait
{
    /**
     * @param array $data
     *
     * @param string $errorStr
     *
     * @return bool
     */
    public function add(array $data, string &$errorStr = ''): bool
    {
        $validator = $this->getAddValidator();
        if (!$validator->checkRequiredAttributes($data)) {
            $errorStr = json_encode($validator->getErrors());
            return false;
        }

        $data = $this->prepareAttributes($data);

        $command = $this->getName() . ' -a';
        $command .= PHP_EOL;

        foreach ($data as $property_key => $property_value) {
            $command .= $property_key . ' ' . $property_value;
            $command .= PHP_EOL;
        }

        $command .= 'ok' . PHP_EOL;

        $result = $this->session->runCommand($command, $this->isHeavy());
        if (false !== stripos($result, 'successfully')) {
            if ($this->isNeedPersist()) {
                $this->session->persist();
            }

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
     * Return mutable version of data
     *
     * @param array $data
     *
     * @return array
     */
    protected function prepareAttributes(array $data): array
    {
        return $data;
    }
}