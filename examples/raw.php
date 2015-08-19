<?php
/**
 * Created by PhpStorm.
 * User: topgan1
 * Date: 8/20/15
 * Time: 12:11 AM
 */

$fp = fsockopen('127.0.0.1', 8990);
//read first line
// should see the Username: prompt
var_dump(trim(fread($fp, 2048)));


//send usernaem
fwrite($fp, "jcliadmin\r");
fwrite($fp, "jclipwd\r");
fwrite($fp, chr(0));
usleep(25000);
echo "<br>Username and pass sent, RESPONSE:";
var_dump(trim(fread($fp, 2048)));


//send help command
fwrite($fp, "help\r");
fwrite($fp, " ");
usleep(25000);
echo "<br>help send:";
$response = trim(fread($fp, 2048));
$response = explode("\r", $response);
var_dump($response);
//echo fgets($fp, 8192);

//$response = $this->getResponse();

//var_dump($response);

