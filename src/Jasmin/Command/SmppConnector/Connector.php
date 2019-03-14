<?php

namespace JasminWeb\Jasmin\Command\SmppConnector;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;
use JasminWeb\Jasmin\Command\ChangeStateTrait;

class Connector extends BaseCommand
{
    use ChangeStateTrait;

    /**
     * @return AddValidator
     */
    protected function getAddValidator(): AddValidator
    {
        return new SmppConnectorAddValidator();
    }

    protected function getName(): string
    {
        return 'smppccm';
    }

    /**
     * @param array $exploded
     * @return array
     */
    protected function parseList(array $exploded): array
    {
        $connectors = [];
        foreach ($exploded as $expl) {
            $row = trim($expl);

            $ff = strstr($expl, 'Total connectors:', true);
            if (!empty($ff)) {
                $row = trim($ff);
            }

            $temp_row = explode(' ', $row);
            $temp_row = array_filter($temp_row);

            $fixed_row = array();
            foreach ($temp_row as $temp){
                $fixed_row[] = $temp;
            }

            $connector['cid'] = $fixed_row[0];
            $connector['service'] = $fixed_row[1];
            $connector['session'] = $fixed_row[2];
            $connector['starts'] = (int) ($fixed_row[3] ?? 0);
            $connector['stops'] = (int) ($fixed_row[4] ?? 0);

            $connectors[] = $connector;
        }

        return $connectors;
    }

    /**
     * @param string $key
     * @return bool
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function enable(string $key): bool
    {
        $r = $this->session->runCommand($this->getName() . ' -1 ' . $key , true);
        return $this->parseResult($r);
    }

    /**
     * @param string $key
     * @return bool
     * @throws \JasminWeb\Exception\ConnectorException
     */
    public function disable(string $key)
    {
        $r = $this->session->runCommand($this->getName() . ' -0 ' . $key, true);
        return $this->parseResult($r);
    }

    protected function isHeavy(): bool
    {
        return true;
    }
}