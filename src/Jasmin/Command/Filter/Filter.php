<?php

namespace JasminWeb\Jasmin\Command\Filter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class Filter extends BaseCommand
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
        return 'filter';
    }

    /**
     * @param array $exploded
     * @return array
     */
    protected function parseList(array $exploded): array
    {
        $filters = [];
        foreach ($exploded as $expl) {
            $filter = trim($expl);

            $ff = strstr($expl, 'Total Filters:', true);
            if (!empty($ff)) {
                $filter = trim($ff);
            }

            $temp_filter = explode(' ', $filter);
            $temp_filter = array_filter($temp_filter);

            $fixed_connector = [];
            foreach ($temp_filter as $temp){
                $fixed_connector[] = $temp;
            }
            $filters[] = [
                'fid'         => $fixed_connector[0],
                'type'        => $fixed_connector[1],
                'routes'      => $fixed_connector[2],
                'description' => $fixed_connector[3],
            ];
        }

        return $filters;
    }
}