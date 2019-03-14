<?php

namespace JasminWeb\Test\Command\MoRouter;

use JasminWeb\Jasmin\Command\MoRouter\MoRouter;
use JasminWeb\Test\BaseTest;

class MoRouterCommandTest extends BaseTest
{
    /**
     * @var MoRouter
     */
    private $moRouter;

    protected function setUp()
    {
        if (!$this->moRouter) {
            $this->moRouter = new MoRouter($this->getSession());
        }
    }

    public function testListMoRouters()
    {
        $list = $this->moRouter->all();
        $this->assertNotEmpty($list);
        $row = array_shift($list);
        $this->assertArrayHasKey('order', $row);
        $this->assertInternalType('int', $row['order']);
        $this->assertArrayHasKey('type', $row);
        $this->assertArrayHasKey('connectors', $row);
        $this->assertInternalType('array', $row['connectors']);
        $this->assertArrayHasKey('filters', $row);
        $this->assertInternalType('array', $row['filters']);
    }
}
