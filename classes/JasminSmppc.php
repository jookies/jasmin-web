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
 *
 * * smppc = SmppClientConnector()
 * smppc.cid = 'ConnectorID'
 * smppc.host = '127.0.0.1'
 * smppc.port = 2775
 * smppc.username = 'foo'
 * smppc.password = 'bar'
 * smppc.save()
 */
class JasminSmppc extends JasminConnector
{
    var $cid;
    var $host;
    var $port;
    var $username;
    var $password;
    var $bind;
    var $bind_ton;
    var $bind_to;
    var $coding;
    var $submit_throughput;

    var $priority;
    var $validity;
    var $dlr_expiry;

    var $con_fail_delay;
    var $con_fail_retry;
    var $con_loss_retry;
    var $con_loss_delay;

    var $ripf;
    var $elink_interval;
    var $src_addr;
    var $bind_npi;
    var $addr_range;
    var $dst_ton;
    var $res_to;
    var $def_msg_id;
    var $dst_npi;
    var $requeue_delay;
    var $src_npi;
    var $trx_to;
    var $logfile;
    var $systype;
    var $loglevel;
    var $proto_id;
    var $pdu_red_to;
    var $src_ton;

    var $command;

    public function save()
    {
        $this->command = '';
        $params = get_class_vars(__CLASS__);

        foreach ($params as $p_key => $p_value)
        {
            echo $this->$$p_key;
            $this->make_cmd($this->$$p_key, $p_value);
        }
        var_dump($params);
        echo $this->cid;
        echo $this->command;
    }

    public function delete()
    {

    }

    private function make_cmd($key, $value)
    {
        if (!empty($value))
        {
            $this->command .= $key . ' ' . $value . '\r\n';
        }

    }
}