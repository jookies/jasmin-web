<?php
namespace Jookies;

/**
 * Class JasminGroup
 *
 * id property is the gid for that class
 */
class JasminGroup extends JasminObject
{
    var $command = 'group';
    var $properties;

    public function __construct()
    {
        parent::__construct();
    }

    public function set_id($id)
    {
        $this->id = $id;
        $this->properties['gid'] = $id;
    }

    public function getAll()
    {
        $fetch_groups = parent::getAll();

        // Explode jcli command output to fetch groups
        $exploded = explode("#", $fetch_groups);

        // Unset first and second elements that include unwanted results from the command group -l
        unset($exploded[0]);
        unset($exploded[1]);

        $groups = array();
        foreach ($exploded as $expl) {
            $group = trim($expl);

            //fetch string before the "Total Groups:" lectic
            $ff = strstr($expl, 'Total Groups:', true);
            if (!empty($ff)) {
                $group = trim($ff);
            }

            array_push($groups, $group);
        }

        return $groups;

    }
}