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
 * Init proxy for connection object
 */
$manager = new \JasminWeb\Jasmin\Connector($connection);
/**
 * Set minimal parameter (cid)
 */
$manager->setId('test_new_one');

/**
 * Save new connector
 */
$manager->save();