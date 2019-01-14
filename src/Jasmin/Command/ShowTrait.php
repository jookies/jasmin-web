<?php

namespace JasminWeb\Jasmin\Command;

trait ShowTrait
{
    /**
     * @param string $key
     * @return array
     */
    public function show(string $key): array
    {
        $response = $this->session->runCommand($this->getName() . ' -s ' . $key);

        $exploded = explode("\n", $response);
        unset($exploded[0]);

        return $this->parseShow($exploded);
    }

    /**
     * @param array $exploded
     * @return array
     */
    abstract protected function parseShow(array $exploded): array;
}