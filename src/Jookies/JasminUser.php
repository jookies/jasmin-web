<?php
namespace Jookies;

/**
 * Class JasminUser
 *
 * id property is the uid for that class
 */
class JasminUser extends JasminObject
{
    var $command = 'user';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['uid'] = $id;
    }

    public function getAll()
    {
        $users = parent::getAll();
        // Explode jcli command output to fetch groups
        $exploded = explode("#", $users);

        // Unset first and second elements that include unwanted results from the command group -l
        unset($exploded[0]);
        unset($exploded[1]);

        $users = array();
        foreach ($exploded as $expl) {
            $user = trim($expl);

            //fetch string before the "Total Users:" phrase. This has the last user that was parsed from jcli
            $ff = strstr($expl, 'Total Users:', true);
            if (!empty($ff)) {
                $user = trim($ff);
            }

            $temp_user = explode(" ", $user);
            $temp_user = array_filter($temp_user);

            $fixed_user = array();
            foreach ($temp_user as $temp){
                array_push($fixed_user, $temp);
            }

            $userz['uid'] = $fixed_user[0];
            $userz['gid'] = $fixed_user[1];
            $userz['username'] = $fixed_user[2];
            $userz['balance'] = $fixed_user[3];
            $userz['mt'] = $fixed_user[4];
            $userz['throughput'] = $fixed_user[5];

            array_push($users, $userz);
        }

        return $users;
    }
}