<?php

namespace JasminWeb\Jasmin\Command;

trait DisableTrait
{
    /**
     * @param string $key
     * @return bool
     */
    public function enable(string $key): bool
    {
        $r = $this->session->runCommand($this->getName() . ' -e ' . $key);
        return $this->parseResult($r, 1);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function disable(string $key): bool
    {
        $r = $this->session->runCommand($this->getName() . ' -d ' . $key);
        return $this->parseResult($r, 0);
    }

    /**
     * @param string $result
     * @param int $mode
     * @return bool
     */
    private function parseResult(string $result, int $mode): bool
    {
        return false !== strpos($result, 'Successfully ' . ($mode ? 'enabled' : 'disabled'));
    }
}