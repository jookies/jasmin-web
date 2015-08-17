<?php
/**
 * Class JasminSmppc
 *
 * id property is the cid for that class
 */
class JasminSmppc extends JasminObject
{
    var $id;
    var $command = 'smppc';
    var $properties;
    /*var $properties = array(
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
    );*/

    public function __construct()
    {
        $this->properties['cid'] = $this->id;
        parent::__construct();
    }

    /**
     * start()
     *
     * Starts the smpp connection
     *
     * @return null|string
     */
    public function start()
    {
        $result = $this->telnet->doCommand($this->command . ' -1 ' . $this->id);

        return $result;
    }

    /**
     * stop()
     *
     * Stops the smpp connection
     *
     * @return null|string
     */
    public function stop()
    {
        $result = $this->telnet->doCommand($this->command . ' -0 ' . $this->id);

        return $result;

    }
}