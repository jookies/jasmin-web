<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

namespace JasminWeb\Jasmin\Filter;

use JasminWeb\Jasmin\TelnetConnector;

class GroupFilter extends Filter
{
    protected $requiredAttributes = ['fid', 'type', 'gid'];
}