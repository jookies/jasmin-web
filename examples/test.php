<?php
namespace Jookies;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';
echo"START<br/>";

$groupconn = new JasminGroup();
$userconn = new JasminUser();
$filterconn = new JasminFilter();

echo "<h2>Group addition</h2>";
$groupconn->set_id('customers1');
if ($groupconn->save()){
    echo 'Group customers1 was created<br/>';
}else{
    echo 'Group customers1 was NOT created<br/>';
}

$groupconn->set_id('customers2');
if ($groupconn->save()){
    echo 'Group customers2 was created<br/>';
}else{
    echo 'Group customers2 was NOT created<br/>';
}


echo "<h2>Group Listing</h2>";
var_dump( $groupconn->getAll());



//DELETION
echo "<h2>Group Deletion</h2>";
$groupconn->set_id('customers1');
if ($groupconn->delete()){
    echo 'Group customers1 was deleted<br/>';
}else{
    echo 'Group customers1 was NOT deleted<br/>';
}


$groupconn->set_id('customers2');
if ($groupconn->delete()){
    echo 'Group customers2 was deleted<br/>';
}else{
    echo 'Group customers2 was NOT deleted<br/>';
}

$groupconn->set_id('customers3');
if ($groupconn->delete()){
    echo 'Group customers3 was deleted<br/>';
}else{
    echo 'Group customers3 was NOT deleted<br/>';
}

$groupconn->set_id('test odiki');
if ($groupconn->delete()){
    echo 'Group test odiki was deleted<br/>';
}else{
    echo 'Group test odiki was NOT deleted<br/>';
}

echo "<h2>Group Listing</h2>";

var_dump($groupconn->getAll());


$userconn->set_id('sotiris');
$userconn->properties['username'] = 'sotiris';
$userconn->properties['password'] = 'sotiris';
$userconn->properties['gid'] = 'customers3';
echo $userconn->save();
echo  "<br/>END";
//$smppconn = new JasminSMPPClientConnector();

//$smppconn->cid = 'test';
//$smppconn->host = 'host_test';
//$smppconn->save();