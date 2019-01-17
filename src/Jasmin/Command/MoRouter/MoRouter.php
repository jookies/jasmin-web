<?php

namespace JasminWeb\Jasmin\Command\MoRouter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class MoRouter extends BaseCommand
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
        return 'morouter';
    }

    /**
     * @param array $exploded
     * @return array
     */
    protected function parseList(array $exploded): array
    {
        $routers = [];
        foreach ($exploded as $expl) {
            $router = trim($expl);

            $ff = strstr($expl, 'Total MO Routes:', true);
            if (!empty($ff)) {
                $router = trim($ff);
            }

            $router = preg_replace(['/\s{2,}/', '/(<\w)(\s)?/'], [' ','$1'], $router);
            $fixed_routers = explode(' ', $router);

            $row = [
                'order'     => (int)array_shift($fixed_routers),
                'type'      => (int)array_shift($fixed_routers),
                'connectors' => [],
                'filters'   => [],
            ];

            foreach ($fixed_routers as $temp) {
                $temp = str_replace(',', '', $temp);
                if (false !== strpos($temp, 'http') || false !== strpos($temp, 'smpps')) {
                    $row['connectors'][] = $temp;
                }

                if (false !== strpos($temp, '<')) {
                    $row['filters'][] = $temp;
                }

            }

            $routers[] = $row;
        }

        return $routers;
    }
}