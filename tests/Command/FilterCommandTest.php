<?php

namespace JasminWeb\Test\Command;

use JasminWeb\Jasmin\Command\Filter\Filter;
use JasminWeb\Test\BaseTest;

class FilterCommandTest extends BaseTest
{
    /**
     * @var Filter
     */
    private $filter;

    protected function setUp()
    {
        if (!$this->filter) {
            $this->filter = new Filter($this->getSession());
        }
    }

    public function testAllFilters()
    {
        $list = $this->filter->all();
        $this->assertNotEmpty($list);
        $row = array_shift($list);
        $this->assertArrayHasKey('fid', $row);
        $this->assertArrayHasKey('type', $row);
        $this->assertArrayHasKey('routes', $row);
        $this->assertArrayHasKey('description', $row);
    }
}
