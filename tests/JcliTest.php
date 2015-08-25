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
        $this->filterconn = new Jookies\JasminFilter();
    }

    public function test_group_addition()
    {
        $this->groupconn->set_id('customers2');
        $this->groupconn->save();

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
        $this->userconn->set_id('sotos');
        $this->userconn->properties['gid'] = 'customers2';

        $this->assertTrue($this->userconn->save());
    }
    public function test_user_addition2()
    {
        $this->userconn->properties['username'] = 'sotiris';
        $this->userconn->properties['password'] = 'sotiris';
        $this->userconn->set_id('sotiris');
        $this->userconn->properties['gid'] = 'customers2';

        $this->assertTrue($this->userconn->save());
    }
    public function test_user_deletion()
    {
        $this->userconn->set_id('sotos');
        $this->assertTrue($this->userconn->delete());
    }
/*
    public function test_filter_addition()
    {
        $this->filterconn->set_id('country');
        $this->filterconn->parameters['type'] = 'UserFilter';
        $this->filterconn->parameters['uid'] = 'sotos';
        $this->assertTrue($this->filterconn->save());
    }*/
}
