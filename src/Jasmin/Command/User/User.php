<?php

namespace JasminWeb\Jasmin\Command\User;

use JasminWeb\Jasmin\Command\AddValidator;
use JasminWeb\Jasmin\Command\BaseCommand;
use JasminWeb\Jasmin\Command\ChangeStateTrait;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class User extends BaseCommand
{
    use ChangeStateTrait;

    /**
     * @return AddValidator
     */
    protected function getAddValidator(): AddValidator
    {
        return new UserAddValidator();
    }

    protected function getName(): string
    {
        return 'user';
    }

    /**
     * @param array $exploded
     * @return array
     */
    protected function parseList(array $exploded): array
    {
        $users = [];
        foreach ($exploded as $item) {
            $user = trim($item);

            $ff = strstr($item, 'Total Users:', true);
            if (!empty($ff)) {
                $user = trim($ff);
            }

            $temp_user = explode(' ', $user);
            $temp_user = array_filter($temp_user);

            $fixed_connector = [];
            foreach ($temp_user as $temp) {
                $fixed_connector[] = $temp;
            }

            $users[] = [
                'uid'        => $fixed_connector[0],
                'gid'        => $fixed_connector[1],
                'username'   => $fixed_connector[2],
                'balance'    => $fixed_connector[3],
                'mt'         => $fixed_connector[4],
                'sms'        => $fixed_connector[5],
                'throughput' => $fixed_connector[6],
            ];
        }

        return $users;
    }

    /**
     * @param array $exploded
     * @return array
     */
    protected function parseShow(array $exploded): array
    {
        $options = [];
        foreach ($exploded as $row) {
            $user = trim($row);

            if (false !== strpos($user, 'jcli :')) {
                continue;
            }

            $values = explode(' ', $user);
            $last = $values[($c = count($values)) - 1];
            $first = array_shift($values);

            $item[$first] = [];
            $current = &$item[$first];
            foreach ($values as $value) {
                $value = trim($value);
                if ($c === 2 || $value === $last) {
                    $current = $last;
                    break;
                }

                $current[$value] = [];
                $current = &$current[$value];
            }

            $options[] = $item;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($options),
            RecursiveIteratorIterator::CATCH_GET_CHILD
        );
        foreach ($iterator as $key => $leaf) {
            echo "$key => $leaf", PHP_EOL;
        }

        return $options;
    }
}