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
 * Set user uid
 */
$manager->setId('test_new_one');
/**
 * Save new user.
 */
$manager->delete();