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
        return new FilterAddValidator();
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
            foreach ($temp_filter as $temp) {
                $fixed_connector[] = $temp;
            }

            $row = [];
            $row['fid'] = $fixed_connector[0];
            $row['type'] = $fixed_connector[1];
            $row['description'] = substr($filter, strpos($filter, '<'), strpos($filter, '>'));
            $row['routes'] = [];

            if (false !== strpos($filter, 'MT')) {
                $row['routes'][] = 'MT';
            }

            if (false !== strpos($filter, 'MO')) {
                $row['routes'][] = 'MO';
            }

            $filters[] = $row;
        }

        return $filters;
    }
}