<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command\User;

use JasminWeb\Jasmin\Command\ListCommand;

class UserListCommand extends ListCommand
{
    protected function parseResponseAfterParent(array $data): array
    {
        $keys = [
            'uid',
            'gid',
            'username',
            'balance',
            'mt',
            'sms',
            'throughput',
        ];

        $users = [];
        foreach ($data as $item) {
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

            $users[] = array_combine($keys, $fixed_connector);
        }

        return $users;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'user';
    }
}