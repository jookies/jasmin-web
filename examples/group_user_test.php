<?php
namespace Jookies;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';
echo "<h1>Jasmin-web API Examples START</h1>";

$groupconn = new JasminGroup();
$userconn = new JasminUser();
$filterconn = new JasminFilter();

echo "<h2>Group addition</h2>";
$groupconn->set_id('customers1');
if ($groupconn->save())
{
    echo 'Group customers1 was created<br/>';
} else
{
    echo 'Group customers1 was NOT created<br/>';
}

$groupconn->set_id('customers2');
if ($groupconn->save())
{
    echo 'Group customers2 was created<br/>';
} else
{
    echo 'Group customers2 was NOT created<br/>';
}


echo "<h2>Group Listing</h2>";
echo "<pre>";
var_dump($groupconn->getAll());
echo "</pre>";



//DELETION
echo "<h2>Group Deletion</h2>";
$groupconn->set_id('customers1');
if ($groupconn->delete())
{
    echo 'Group customers1 was deleted<br/>';
} else
{
    echo 'Group customers1 was NOT deleted<br/>';
}


$groupconn->set_id('customers3');
if ($groupconn->delete())
{
    echo 'Group customers3 was deleted<br/>';
} else
{
    echo 'Group customers3 was NOT deleted<br/>';
}

echo "<h2>Group Listing</h2>";
echo "<pre>";
var_dump($groupconn->getAll());
echo "</pre>";

// User creation
echo "<h2>User Creation</h2>";

$userconn->set_id('sotiris');
$userconn->properties['username'] = 'sotiris';
$userconn->properties['password'] = 'sotiris';
$userconn->properties['gid'] = 'customers2';
if ($userconn->save())
{
    echo 'User ' . $userconn->properties['uid'] . ' was created<br/>';
} else
{
    echo 'User ' . $userconn->properties['uid'] . ' was NOT created<br/>';
}


$userconn->set_id('sotiris2');
$userconn->properties['username'] = 'sotiris2';
$userconn->properties['password'] = 'sotiris2';
$userconn->properties['gid'] = 'customers2';
if ($userconn->save())
{
    echo 'User ' . $userconn->properties['uid'] . ' was created<br/>';
} else
{
    echo 'User ' . $userconn->properties['uid'] . ' was NOT created<br/>';
}


echo "<h2>User Listing</h2>";
echo "<pre>";
var_dump($userconn->getAll());
echo "</pre>";
echo "<br/>END";
//$smppconn = new JasminSMPPClientConnector();

//$smppconn->cid = 'test';
//$smppconn->host = 'host_test';
//$smppconn->save();