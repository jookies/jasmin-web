<?php

namespace JasminWeb\Jasmin\Command\Connector;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class Connector extends BaseCommand
{
    /**
     * @return AddValidator
     */
    protected function getAddValidator(): AddValidator
    {
        // TODO: Implement getAddValidator() method.
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
            $connector['status'] = $fixed_row[1];
            $connector['session'] = $fixed_row[2];
            $connector['starts'] = $fixed_row[3] ?? 0;
            $connector['stops'] = $fixed_row[4] ?? 0;

            $connectors[] = $connector;
        }

        return $connectors;
    }
}