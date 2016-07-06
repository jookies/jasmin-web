<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 29.06.16
 */

namespace JasminWeb\Jasmin\MtRouter;

use JasminWeb\Jasmin\TelnetConnector;
use JasminWeb\Jasmin\Connector;
use JasminWeb\Jasmin\Filter\Filter;

class StaticMTRoute extends MtRouter
{
    protected $requiredAttributes = ['type', 'order', 'filters', 'connector', 'rate'];

    public function __construct(TelnetConnector $connector)
    {
        parent::__construct($connector);
        $this->attributes['type'] = self::StaticMTRoute;
    }

    public function setConnector($cid)
    {
        $this->attributes['connector'] = strtr('smppc(:cid)', [
            ':cid' => $cid,
        ]);
        return $this;
    }

    public function getConnector()
    {
        if (!isset($this->attributes['connector'])) {
            return '';
        }
        if (preg_match('/smppc\((.*?)\)/', (string)$this->attributes['connector'], $matches)) {
            return $matches[1];
        }
        return '';
    }

    public function add()
    {
        if (!$this->checkRequiredAttribute()) {
            return false;
        }

        // this is not fully correct
        $connectorManager = new Connector($this->connector);
        if (!$connectorManager->checkExist($this->getConnector())) {
            $this->errors['connector'] = strtr('Connector :cid not found at db', [
                ':cid' => $this->getConnector(),
            ]);
            return false;
        }
        unset($this->errors['filters']);
        // this is not fully correct
        $filterManager = new Filter($this->connector);
        foreach ($this->getFilters() as $filter) {
            if (!$filterManager->checkExist($filter)) {
                if (!isset($this->errors['filters'])) {
                    $this->errors['filters'] = [];
                }
                $this->errors['filters'][] = strtr('Filter :fid not found at db', [
                    ':fid' => $filter,
                ]);
            }
        }
        if (isset($this->errors['filters'])) {
            return false;
        }

        return $this->save();
    }
}