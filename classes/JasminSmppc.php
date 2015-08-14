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
class JasminSmppc extends JasminObject
{
    var $key;
    var $command = 'smppc';
    var $properties = array(
        'cid',
        'host',
        'port',
        'username',
        'password',
        'bind',
        'bind_ton',
        'bind_to',
        'coding',
        'submit_throughput',
        'priority',
        'validity',
        'dlr_expiry',
        'con_fail_delay',
        'con_fail_retry',
        'con_loss_retry',
        'con_loss_delay',
        'ripf',
        'elink_interval',
        'src_addr',
        'bind_npi',
        'addr_range',
        'dst_ton',
        'res_to',
        'def_msg_id',
        'dst_npi',
        'requeue_delay',
        'src_npi',
        'trx_to',
        'logfile',
        'systype',
        'loglevel',
        'proto_id',
        'pdu_red_to',
        'src_ton'
    );

    public function __construct()
    {
        parent::__construct();
    }
}