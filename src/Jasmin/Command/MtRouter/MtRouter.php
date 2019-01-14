<?php

namespace JasminWeb\Jasmin\Command\MtRouter;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;

class MtRouter extends BaseCommand
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
        return 'mtrouter';
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

            $router = explode(' ', $router);
            $router = array_filter($router, 'strlen');

            $fixed_routers = [];
            foreach ($router as $temp) {
                $fixed_routers[] = $temp;
            }

            $routers[] = [
                'order'     => (int)$fixed_routers[0],
                'type'      => $fixed_routers[1],
                'rate'      => (float)$fixed_routers[2],
                'connectors' => explode(',', $fixed_routers[3]),
                'filters'   => isset($fixed_routers[4]) ? explode(',', $fixed_routers[4]) : [],
            ];
        }

        return $routers;
    }
}