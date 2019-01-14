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

            $router = explode(' ', $router);
            $router = array_filter($router, 'strlen');

            $fixed_routers = array();
            foreach ($router as $temp) {
                $fixed_routers[] = $temp;
            }

            $routers[] = [
                'order'     => (int)$fixed_routers[0],
                'type'      => $fixed_routers[1],
                'connectors' => explode(',', $fixed_routers[2]),
                'filters'   => isset($fixed_routers[3]) ? explode(',', $fixed_routers[3]) : [],
            ];
        }

        return $routers;
    }
}