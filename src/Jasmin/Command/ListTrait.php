<?php

namespace JasminWeb\Jasmin\Command;

trait ListTrait
{
    /**
     * @return array
     */
    public function all(): array
    {
        $response = $this->session->runCommand($this->getName() . ' -l');

        $exploded = explode('#', $response);
        unset($exploded[0], $exploded[1]);

        return $this->parseList($exploded);
    }

    /**
     * @param array $exploded
     *
     * @return array
     */
    abstract protected function parseList(array $exploded): array;
}