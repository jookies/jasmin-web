<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 20.06.16
 */

namespace JasminWeb\Jasmin\Filter;


class UserFilter extends Filter
{
    protected $required = ['fid', 'type', 'uid'];
}