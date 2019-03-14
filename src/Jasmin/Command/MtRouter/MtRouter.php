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

            $ff = strstr($expl, 'Total MT Routes:', true);
            if (!empty($ff)) {
                $router = trim($ff);
            }

            $router = preg_replace(['/\s{2,}/', '/(<\w)(\s)?/'], [' ','$1'], $router);
            $fixed_routers = explode(' ', $router);

            $row = [
                'order'     => (int)array_shift($fixed_routers),
                'type'      => array_shift($fixed_routers),
                'rate'      => (float)array_shift($fixed_routers),
                'connectors' => [],
                'filters' => []
            ];

            if (false !== strpos($el = current($fixed_routers), '(!)')) {
                array_shift($fixed_routers);
            }

            foreach ($fixed_routers as $temp) {
                $temp = str_replace(',', '', $temp);
                if (false !== strpos($temp, 'smppc')) {
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

    protected function isHeavy(): bool
    {
        return true;
    }
}