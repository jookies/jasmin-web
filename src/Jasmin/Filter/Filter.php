<?php namespace JasminWeb\Jasmin\Filter;

use JasminWeb\Exception\FilterException;
use JasminWeb\Jasmin\BaseObject;
use JasminWeb\Jasmin\TelnetConnector;

/**
 * Class JasminGroup
 *
 * id property is the gid for that class
 */
class Filter extends BaseObject
{
    const TransparentFilter = 'TransparentFilter';
    const ConnectorFilter = 'ConnectorFilter';
    const UserFilter = 'UserFilter';
    const GroupFilter = 'GroupFilter';
    const SourceAddrFilter = 'SourceAddrFilter';
    const DestinationAddrFilter = 'DestinationAddrFilter';
    const ShortMessageFilter = 'ShortMessageFilter';
    const DateIntervalFilter = 'DateIntervalFilter';
    const TimeIntervalFilter = 'TimeIntervalFilter';
    const TagFilter = 'TagFilter';
    const EvalPyFilter = 'EvalPyFilter';

    protected $command = 'filter';

    protected $required = ['fid', 'type'];


    public function getId()
    {
        return $this->attributes['fid'];
    }

    public function setId($id)
    {
        $this->attributes['fid'] = $id;
    }

    public function getAll()
    {
        $fetch_filters = parent::getAll();

        // Explode jcli command output to fetch groups
        $exploded = explode("#", $fetch_filters);

        // Unset first and second elements that include unwanted results from the command group -l
        unset($exploded[0]);
        unset($exploded[1]);

        $groups = [];
        foreach ($exploded as $expl) {
            $filter = trim($expl);

            //fetch string before the "Total Filters:" lectic
            $ff = strstr($expl, 'Total Filters:', true);
            if (!empty($ff)) {
                $filter = trim($ff);
            }

            $temp_filter = explode(" ", $filter);
            $temp_filter = array_filter($temp_filter);

            $fixed_connector = array();
            foreach ($temp_filter as $temp){
                array_push($fixed_connector, $temp);
            }

            $groups['fid'] = $fixed_connector[0];
            $groups['type'] = $fixed_connector[1];
            $groups['routes'] = $fixed_connector[2];
            $groups['description'] = $fixed_connector[3];
        }

        return $groups;
    }

    /**
     * Check is at db exist filter with that gid
     * @param $fid
     * @return bool
     */
    public function checkExist($fid)
    {
        foreach ($this->getAll() as $filter) {
            if ($filter['fid'] == $fid) {
                return true;
            }
        }
        return false;
    }

    public static function getFilter($type, TelnetConnector $connection)
    {
        switch ($type) {
            case (self::TransparentFilter):{
                return new TransparentFilter($connection);
            }
            case (self::ConnectorFilter):{
                return new ConnectorFilter($connection);
            }
            case (self::UserFilter):{
                return new UserFilter($connection);
            }
            case (self::GroupFilter):{
                return new GroupFilter($connection);
            }
            case (self::SourceAddrFilter):{
                return new SourceAddrFilter($connection);
            }
            case (self::DestinationAddrFilter):{
                return new DestinationAddrFilter($connection);
            }
            case (self::ShortMessageFilter):{
                return new ShortMessageFilter($connection);
            }
            case (self::DateIntervalFilter):{
                return new DateIntervalFilter($connection);
            }
            case (self::TimeIntervalFilter):{
                return new TimeIntervalFilter($connection);
            }
            case (self::TagFilter):{
                return new TagFilter($connection);
            }
            case (self::EvalPyFilter):{
                return new EvalPyFilter($connection);
            }
            default:
                throw new FilterException('Try create filter with unknown type');
        }
    }
}