<?php
namespace Jookies;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';

/*
$groupconn = new JasminGroup();


$groupconn->id = 'customers3';
$groupconn->save();

$groupconn->id = 'customers2';
$groupconn->save();


$groupconn->id = 'customers2';
$groupconn->delete();
//$groupconn->getAll();

*/
$user = new JasminUser();

$user->id = 'sotiris';
$user->properties['username'] = 'sotiris';
$user->properties['password'] = 'sotiris';
$user->properties['gid'] = 'customers3';
echo $user->save();
//$smppconn = new JasminSMPPClientConnector();

//$smppconn->cid = 'test';
//$smppconn->host = 'host_test';
//$smppconn->save();