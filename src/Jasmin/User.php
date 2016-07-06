<?php namespace JasminWeb\Jasmin;

/**
 * Class JasminUser
 *
 * id property is the uid for that class
 */
class User extends BaseObject
{
    protected $command = 'user';

    protected $requiredAttributes = ['uid', 'username', 'password', 'gid'];

    public function getId()
    {
        return $this->attributes['uid'];
    }

    public function setId($id)
    {
        $this->attributes['uid'] = $id;
    }

    public function getAll()
    {
        $connections = parent::getAll();
        // Explode jcli command output to fetch groups
        $exploded = explode("#", $connections);

        // Unset first and second elements that include unwanted results from the command group -l
        unset($exploded[0]);
        unset($exploded[1]);

        $connections = [];
        foreach ($exploded as $expl) {
            $connector = trim($expl);

            //fetch string before the "Total Users:" phrase. This has the last user that was parsed from jcli
            $ff = strstr($expl, 'Total Users:', true);
            if (!empty($ff)) {
                $connector = trim($ff);
            }

            $temp_connector = explode(" ", $connector);
            $temp_connector = array_filter($temp_connector);

            $fixed_connector = array();
            foreach ($temp_connector as $temp){
                array_push($fixed_connector, $temp);
            }

            $connections[] = [
                'uid'        => $fixed_connector[0],
                'gid'        => $fixed_connector[1],
                'username'   => $fixed_connector[2],
                'balance'    => $fixed_connector[3],
                'mt'         => $fixed_connector[4],
                'throughput' => $fixed_connector[5],
            ];
        }

        return $connections;
    }

    /**
     * Check is at db exist user with that uid
     * @param $uid
     * @return bool
     */
    public function checkExist($uid)
    {
        foreach ($this->getAll() as $group) {
            if ($group['uid'] == $uid) {
                return true;
            }
        }
        return false;
    }

    /**
     * Save new user at db
     * If gid not exist than create new group with gid
     * @return bool
     */
    public function add()
    {
        if (!$this->checkRequiredAttribute()) {
            return false;
        }

        // this is not fully correct
        $groupManager = new Group($this->connector);
        if (!$groupManager->checkExist($this->attributes['gid'])) {
            $groupManager->setId($this->attributes['gid']);
            $groupManager->save();
        }

        if (!$groupManager->checkExist($this->attributes['gid'])) {
            $this->errors['gid'] = 'Can\'t get group id';
            return false;
        }

        return $this->save();
    }
}