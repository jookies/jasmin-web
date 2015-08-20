<?php
require __DIR__ . "/../vendor/autoload.php";

class JcliTest extends PHPUnit_Framework_TestCase
{

    var $groupconn;
    var $userconn;

    public function __construct()
    {
        $this->groupconn = new Jookies\JasminGroup();
        $this->userconn = new Jookies\JasminUser();
    }

    public function test_group_addition()
    {
        $this->groupconn->set_id('customers');
        $this->assertTrue($this->groupconn->save());
//        $this->assertEquals('Successfully added Group [' . $this->groupconn->properties['gid'] . ']', $this->groupconn->save());
    }

    public function test_group_deletion()
    {
        $this->groupconn->set_id('customers');
        $this->assertTrue($this->groupconn->delete());
    }

    /*public function test_group_getAll()
    {
        $this->groupconn->getAll();
    }*/

    public function test_user_addition()
    {
        $this->userconn->properties['username'] = 'sotos';
        $this->userconn->properties['password'] = 'sotos';
        $this->userconn->set_id('sotiris');
        $this->userconn->properties['gid'] = 'customers3';

        $this->assertTrue($this->userconn->save());
    }

    public function test_user_deletion()
    {
        $this->userconn->set_id('sotiris');
        $this->assertTrue($this->userconn->delete());
    }
}
