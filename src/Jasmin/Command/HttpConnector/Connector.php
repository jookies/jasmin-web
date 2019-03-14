<?php

namespace JasminWeb\Jasmin\Command\HttpConnector;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class Connector extends BaseCommand
{
    /**
     * @return AddValidator
     */
    protected function getAddValidator(): AddValidator
    {
        return new HttpConnectorAddValidator();
    }

    protected function getName(): string
    {
        return 'httpccm';
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

            $ff = strstr($expl, 'Total Httpccs:', true);
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
            $connector['type'] = $fixed_row[1];
            $connector['method'] = $fixed_row[2];
            $connector['url'] = $fixed_row[3];

            $connectors[] = $connector;
        }

        return $connectors;
    }

    protected function isHeavy(): bool
    {
        return true;
    }
}