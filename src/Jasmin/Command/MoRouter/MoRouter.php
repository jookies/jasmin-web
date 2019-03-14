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
        return new MoRouterBaseAddValidator();
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
                'type'      => array_shift($fixed_routers),
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

    protected function isHeavy(): bool
    {
        return true;
    }

    public function prepareAttributes(array $data): array
    {
        if (isset($data['filters']) && !empty($data['filters'])) {
            $data['filters'] = implode(';', $data['filters']);
        }

        if (isset($data['connectors']) && !empty($data['filters'])) {
            $data['connectors'] = implode(';', $data['connectors']);
        }

        return $data;
    }
}