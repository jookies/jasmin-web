<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 27.06.16
 */

namespace JasminWeb\Jasmin\MoRouter;

use JasminWeb\Jasmin\TelnetConnector;
use JasminWeb\Jasmin\Connector;
use JasminWeb\Jasmin\Filter\Filter;

class StaticMORoute extends MoRouter
{
    protected $requiredAttributes = ['type', 'order', 'filters', 'connector'];

    public function __construct(TelnetConnector $connector)
    {
        parent::__construct($connector);
        $this->attributes['type'] = self::StaticMORoute;
    }

    public function setConnector($cid)
    {
        $this->attributes['connector'] = strtr('smpps(:cid)', [
            ':cid' => $cid,
        ]);
        return $this;
    }

    public function getConnector()
    {
        if (!isset($this->attributes['connector'])) {
            return '';
        }
        if (preg_match('/smpps\((.*?)\)/', (string)$this->attributes['connector'], $matches)) {
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