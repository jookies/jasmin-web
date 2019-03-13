<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command\Group;

use JasminWeb\Jasmin\Command\AddCommand;
use JasminWeb\Jasmin\Response\Response;

class GroupAddCommand extends AddCommand
{
    public function validate(): bool
    {
        $diff = array_diff(['gid'], array_keys($this->getArguments()));

        return empty($diff);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'group';
    }

    /**
     * Parses a raw response and returns a PHP object.
     *
     * @param string $data Binary string containing the whole response.
     *
     * @return mixed
     */
    public function parseResponse(string $data): Response
    {
        $resp = new Response();
        if (false !== stripos($data, 'successfully added')) {
            return $resp;
        }

        return $resp->setErrorMessage($data);
    }
}