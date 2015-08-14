<?php
/**
 * Created by PhpStorm.
 * User: topgan1
 * Date: 14/8/15
 * Time: 01:15
 */

function __autoload($className)
{
    if (file_exists('classes/' . $className . '.php'))
    {
        require_once 'classes/' . $className . '.php';

        return true;
    }

    return false;
}