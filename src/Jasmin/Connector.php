<?php namespace JasminWeb\Jasmin;

/**
 * Class JasminUser
 *
 * id property is the uid for that class
 */
class Connector extends BaseObject
{
    protected $command = 'smppccm';

    protected $required = ['cid', 'username', 'password', 'port'];

    public function getId()
    {
        return $this->attributes['cid'];
    }

    public function setId($id)
    {
        $this->attributes['cid'] = $id;
    }

    public function getAll()
    {
        $users = parent::getAll();
        // Explode jcli command output to fetch groups
        $exploded = explode("#", $users);

        // Unset first and second elements that include unwanted results from the command group -l
        unset($exploded[0]);
        unset($exploded[1]);

        $users = [];
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

            $userz['cid'] = $fixed_user[0];
            $userz['status'] = $fixed_user[1];
            $userz['session'] = $fixed_user[2];
            $userz['starts'] = isset($fixed_user[3]) ? $fixed_user[3] : 0;
            $userz['stops'] = isset($fixed_user[4]) ? $fixed_user[4] : 0;

            array_push($users, $userz);
        }

        return $users;
    }

    /**
     * Check is at db exist smppccm with that uid
     * @param $cid
     * @return bool
     */
    public function checkExist($cid)
    {
        foreach ($this->getAll() as $group) {
            if ($group['cid'] == $cid) {
                return true;
            }
        }
        return false;
    }
}