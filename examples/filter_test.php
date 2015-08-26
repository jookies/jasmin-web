<?php
namespace Jookies;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';
echo "<h1>Jasmin-web API Examples for Filters</h1>";

$filterconn = new JasminFilter();

$filterconn->set_id('country_filter');
$filterconn->parameters['type'] = 'GroupFilter';
$filterconn->parameters['gid'] = 'customers';
if ($filterconn->save()){
    echo "Filter ok";
}else{
    echo "filter not ok";
}