<?php namespace JasminWeb\Jasmin;

/**
 * Class JasminGroup
 *
 * id property is the gid for that class
 */
class Group extends BaseObject
{
    protected $command = 'group';

    protected $required = ['gid'];


    public function getId()
    {
        return $this->attributes['gid'];
    }

    public function setId($id)
    {
        $this->attributes['gid'] = $id;
    }

    public function getAll()
    {
        $fetch_groups = parent::getAll();

        // Explode jcli command output to fetch groups
        $exploded = explode("#", $fetch_groups);

        // Unset first and second elements that include unwanted results from the command group -l
        unset($exploded[0]);
        unset($exploded[1]);

        $groups = [];
        foreach ($exploded as $expl) {
            $group = trim($expl);

            //fetch string before the "Total Groups:" lectic
            $ff = strstr($expl, 'Total Groups:', true);
            if (!empty($ff)) {
                $group = trim($ff);
            }
            $groups[] = ['gid' => $group];
        }

        return $groups;
    }

    /**
     * Check is at db exist group with that gid
     * @param $gid
     * @return bool
     */
    public function checkExist($gid)
    {
        foreach ($this->getAll() as $group) {
            if ($group['gid'] == $gid) {
                return true;
            }
        }
        return false;
    }
}