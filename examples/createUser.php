<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 06.07.16
 */
use JasminWeb\Jasmin\TelnetConnector as JasminConnector;

/**
 * Init connection for jasmin
 */
$connection = JasminConnector::init('jcliadmin', 'jclipwd');

/**
 * Init new proxy for user object
 */
$manager = new \JasminWeb\Jasmin\User($connection);
/**
 * Set new user param
 */
$manager->setId('test_new_one');
$manager->attributes['username'] = 'test_new_one';
$manager->attributes['password'] = '12345';
$manager->attributes['gid'] = 'test_new_one';
/**
 * Save new user.
 */
$manager->add();