<?php

namespace JasminWeb\Test\Command\Filter;

use JasminWeb\Jasmin\Command\Filter\Filter;
use JasminWeb\Jasmin\Command\Group\Group;
use JasminWeb\Jasmin\Command\User\User;
use JasminWeb\Jasmin\Connection\Session;
use JasminWeb\Test\BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

class FilterCommandTest extends BaseTest
{
    /**
     * @var MockObject|Session
     */
    private $session;

    /**
     * @var Filter
     */
    private $filter;

    public function setUp()
    {
        if (!$this->session && $this->isRealJasminServer()) {
            $this->session = $this->getSession();
        } else {
            $this->session = $this->getSessionMock();
        }

        $this->filter = new Filter($this->session);
    }

    public function testEmptyList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Filter id        Type                   Routes Description
Total Filters: 0
STR;
            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->filter->all();

        $this->assertEmpty($list);
    }

    /**
     * @depends testEmptyList
     */
    public function testFakeNonEmptyList(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Filter id        Type                   Routes Description
#StartWithHello   ShortMessageFilter     MO MT  <ShortMessageFilter (msg=^hello.*$)>
#ExternalPy       EvalPyFilter           MO MT  <EvalPyFilter (pyCode= ..)>
#To85111          DestinationAddrFilter  MO MT  <DestinationAddrFilter (dst_addr=^85111$)>
#September2014    DateIntervalFilter     MO MT  <DateIntervalFilter (2014-09-01,2014-09-30)>
#WorkingHours     TimeIntervalFilter     MO MT  <TimeIntervalFilter (08:00:00,18:00:00)>
#TF               TransparentFilter      MO MT  <TransparentFilter>
#TG-Spain-Vodacom TagFilter              MO MT  <TG (tag=21401)>
#From20*          SourceAddrFilter       MO     <SourceAddrFilter (src_addr=^20\d+)>
#f_2              ConnectorFilter        MO     <C (cid=c_1)>
#f_3              UserFilter             MT     <U (uid=u_1)>
Total Filters: 10
STR;
            $this->session->method('runCommand')->willReturn($listStr);
            $list = $this->filter->all();
            $this->assertCount(10, $list);
            foreach ($list as $row) {
                $this->assertArrayHasKey('fid', $row);
                $this->assertInternalType('string', $row['fid']);
                $this->assertArrayHasKey('type', $row);
                $this->assertContains($row['type'], [
                    'ShortMessageFilter',
                    'EvalPyFilter',
                    'DestinationAddrFilter',
                    'DateIntervalFilter',
                    'TimeIntervalFilter',
                    'TransparentFilter',
                    'TagFilter',
                    'ConnectorFilter',
                    'UserFilter',
                    'SourceAddrFilter',
                ]);
                $this->assertArrayHasKey('routes', $row);
                $this->assertInternalType('array', $row['routes']);
                $this->assertArrayHasKey('description', $row);
                $this->assertInternalType('string', $row['description']);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @depends testFakeNonEmptyList
     */
    public function testAddUserFilter(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully added');
        }

        (new Group($this->session))->add([
            'gid' => 'jTestG1',
        ]);

        (new User($this->session))->add([
            'uid' => 'jTestU1',
            'gid' => 'jTestG1',
            'username' => 'jTestUN1',
            'password' => 'jTestP1'
        ]);

        $errstr = '';
        $this->assertTrue($this->filter->add([
            'type' => 'userfilter',
            'fid' => 'jTestF1',
            'uid' => 'jTestU1'
        ], $errstr), $errstr);
    }

    /**
     * @depends testAddUserFilter
     */
    public function testListAfterAddUserFilter(): void
    {
        if (!$this->isRealJasminServer()) {
            $listStr = <<<STR
#Filter id        Type                   Routes Description
#jTestF1              UserFilter             MT     <U (uid=JTestU1)>
Total Filters: 1
STR;

            $this->session->method('runCommand')->willReturn($listStr);
        }

        $list = $this->filter->all();
        $this->assertCount(1, $list);

        $row = array_shift($list);
        $this->assertEquals('jTestF1', $row['fid']);
        $this->assertEquals('UserFilter', $row['type']);
    }

    /**
     * @depends testListAfterAddUserFilter
     */
    public function testRemoveFilter(): void
    {
        if (!$this->isRealJasminServer()) {
            $this->session->method('runCommand')->willReturn('Successfully');
        }

        $this->assertTrue($this->filter->remove('jTestF1'));
        $this->testEmptyList();

        (new Group($this->session))->remove('jTestG1');
    }
}
