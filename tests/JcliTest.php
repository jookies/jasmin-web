<?php
require '../vendor/autoload.php';

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
        $this->groupconn->id = 'customers3';
        $this->assertEquals('Successfully added Group [' . $this->groupconn->id . ']', $this->groupconn->save());
    }

    public function test_group_deletion()
    {
        $this->groupconn->id = 'customers2';
        $this->assertEquals('Successfully removed Group id:' . $this->groupconn->id, $this->groupconn->delete());
    }

    public function test_group_getAll()
    {
        $this->groupconn->getAll();
    }

    public function test_user_addition()
    {
        $this->userconn->properties['username'] = 'sotos';
        $this->userconn->properties['password'] = 'sotos';
        $this->userconn->properties['uid'] = 'sotos';
        $this->userconn->properties['gid'] = 'customers3';

        $this->assertEquals('Successfully added User [' . $this->userconn->id . '] to Group [' . $this->userconn->properties['gid'] . ']', $this->userconn->save());
    }

    public function test_user_deletion()
    {
        $this->userconn->id = 'sotos';
        $this->assertEquals('Successfully Removed User id:' . $this->userconn->id, $this->userconn->delete());
    }
}
