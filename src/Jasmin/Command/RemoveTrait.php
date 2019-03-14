<?php

namespace JasminWeb\Jasmin\Command;

trait RemoveTrait
{
    /**
     * @param string $key
     *
     * @return bool
     */
    public function remove(string $key): bool
    {
        $result = $this->session->runCommand($this->getName() . ' -r ' . $key);

        if (false !== stripos($result, 'successfully')) {
            $this->session->persist();
            return true;
        }

        return false;
    }
}