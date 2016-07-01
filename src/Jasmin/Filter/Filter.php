<?php namespace JasminWeb\Jasmin\Filter;

/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

use JasminWeb\Exception\FilterException;
use JasminWeb\Jasmin\BaseObject;
use JasminWeb\Jasmin\TelnetConnector;

/**
 * Class Filter
 * @package JasminWeb\Jasmin\Filter
 */
class Filter extends BaseObject
{
    /**
     * All.    This filter will always match any message criteria
     */
    const TransparentFilter = 'TransparentFilter';

    /**
     * MO. Will match the source connector of a message
     */
    const ConnectorFilter = 'ConnectorFilter';

    /**
     * MT. Will match the owner of a MT message
     */
    const UserFilter = 'UserFilter';

    /**
     * MT. Will match the owner’s group of a MT message
     */
    const GroupFilter = 'GroupFilter';

    /**
     * All. Will match the source address of a MO message
     */
    const SourceAddrFilter = 'SourceAddrFilter';

    /**
     * All. Will match the source address of a message
     */
    const DestinationAddrFilter = 'DestinationAddrFilter';

    /**
     * All. Will match the content of a message
     */
    const ShortMessageFilter = 'ShortMessageFilter';

    /**
     * All. Will match the date of a message
     */
    const DateIntervalFilter = 'DateIntervalFilter';

    /**
     * All. Will match the time of a message
     */
    const TimeIntervalFilter = 'TimeIntervalFilter';

    /**
     * All. Will check if message has a defined tag
     */
    const TagFilter = 'TagFilter';

    /**
     * All. Will pass the message to a third party python script for user-defined filtering
     */
    const EvalPyFilter = 'EvalPyFilter';

    /**
     * Command for manipulate with data
     * @var string
     */
    protected $command = 'filter';

    /**
     * Required field for save at db
     * @var array
     */
    protected $requiredAttributes = ['fid', 'type'];

    /**
     * Get Id of entity
     * @return mixed
     */
    public function getId()
    {
        return $this->attributes['fid'];
    }

    /**
     * Set Id of entity
     * @param string $id Identity of entity
     * @return mixed
     */
    public function setId($id)
    {
        $this->attributes['fid'] = $id;
    }

    /**
     * Get an array that contain all entities from db
     * @return array
     */
    public function getAll()
    {
        // Explode jcli command output from fetch
        $exploded = explode("#", parent::getAll());

        // Unset first and second elements that include unwanted results from the command  -l
        unset($exploded[0]);
        unset($exploded[1]);

        $filters = [];
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
            $filters[] = [
                'fid'         => $fixed_connector[0],
                'type'        => $fixed_connector[1],
                'routes'      => $fixed_connector[2],
                'description' => $fixed_connector[3],
            ];
        }

        return $filters;
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

    /**
     * Create filter by filter type
     * @param string $type Type of filter that need to be created
     * @param TelnetConnector $connection
     * @return ConnectorFilter|DateIntervalFilter|EvalPyFilter|TagFilter|TimeIntervalFilter|TransparentFilter|UserFilter
     * @throws FilterException
     */
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

    /**
     * Save object with addition validation and actions
     * @throws \Exception
     */
    public function add()
    {
        throw new \Exception('Not realise function for child class');
    }

    /**
     * Saves the attributes for the given command
     *
     * @return bool
     * @throws \JasminWeb\Exception\ConnectorException
     * @throws \Exception
     */
    public function save()
    {
        if (get_class($this) == 'Filter') {
            throw new \Exception('Not realise function for child class');
        }
        return parent::save();
    }
}