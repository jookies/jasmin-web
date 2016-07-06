<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

namespace JasminWeb\Jasmin\Filter;

use JasminWeb\Jasmin\Connector;
use JasminWeb\Jasmin\TelnetConnector;

/**
 * Class ConnectorFilter
 * @package JasminWeb\Jasmin\Filter
 */
class ConnectorFilter extends Filter
{
    protected $requiredAttributes = ['fid', 'type', 'cid'];

    public function __construct(TelnetConnector $connector)
    {
        parent::__construct($connector);
        $this->attributes['type'] = self::ConnectorFilter;
    }

    public function setCId($cid)
    {
        $this->attributes['cid'] = $cid;
        return $this;
    }

    public function getCId()
    {
        return $this->attributes['cid'];
    }

    public function add()
    {
        if (!$this->checkRequiredAttribute()) {
            return false;
        }

        // this is not fully correct
        $userManager = new Connector($this->connector);
        if (!$userManager->checkExist($this->getCId())) {
            $this->errors['cid'] = strtr('Connector :cid not found at db', [
                ':cid' => $this->getCId(),
            ]);
            return false;
        }

        return $this->save();
    }
}