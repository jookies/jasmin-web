<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

namespace JasminWeb\Jasmin\Filter;


use JasminWeb\Jasmin\TelnetConnector;
use JasminWeb\Jasmin\User;

class UserFilter extends Filter
{
    protected $requiredAttributes = ['fid', 'type', 'uid'];

    public function __construct(TelnetConnector $connector)
    {
        parent::__construct($connector);
        $this->attributes['type'] = self::UserFilter;
    }

    public function add()
    {
        if (!$this->checkRequiredAttribute()) {
            return false;
        }

        // this is not fully correct
        $userManager = new User($this->connector);
        if (!$userManager->checkExist($this->attributes['uid'])) {
            $this->errors['uid'] = strtr('Uid :uid not  found at db', [
                ':uid' => $this->attributes['uid']
            ]);
            return false;
        }

        return $this->save();
    }
}