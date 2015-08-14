<?php
/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Author: Sotiris Ganouris topgan1@gmail.com
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
include('classes/JasminGroup.php');
include('classes/JasminSmppc.php');

$groupconn = new JasminGroup();

$groupconn->getAll();

$groupconn->properties['gid'] = 'customers3';
$groupconn->save();

$groupconn->properties['gid'] = 'customers2';
$groupconn->save();


$groupconn->key = 'customers2';
echo $groupconn->delete();
//$smppconn = new JasminSMPPClientConnector();

//$smppconn->cid = 'test';
//$smppconn->host = 'host_test';
//$smppconn->save();