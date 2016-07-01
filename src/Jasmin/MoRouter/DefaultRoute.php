<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 27.06.16
 */

namespace JasminWeb\Jasmin\MoRouter;

use JasminWeb\Jasmin\TelnetConnector;

class DefaultRoute extends MoRouter
{
    protected $requiredAttributes = ['type', 'filters', 'connector'];

    public function __construct(TelnetConnector $connector)
    {
        parent::__construct($connector);
        $this->attributes['type'] = self::StaticMORoute;
    }

    public function setCId($cid)
    {
        $this->attributes['cid'] = strtr('smpps(:cid)', [
            ':cid' => $cid,
        ]);
        return $this;
    }

    public function getCId()
    {
        if (preg_match('/smpps\((.*?)\)/', (string)$this->attributes['cid'], $matches)) {
            return $matches[1];
        }
        return '';
    }
}