<?php

namespace JasminWeb\Test\Command\MtRouter;

use JasminWeb\Jasmin\Command\MtRouter\MtRouter;
use JasminWeb\Test\BaseTest;

class MtRouterCommandTest extends BaseTest
{
    /**
     * @var MtRouter
     */
    private $mtRouter;

    protected function setUp()
    {
        if (!$this->mtRouter) {
            $this->mtRouter = new MtRouter($this->getSession());
        }
    }

    public function testListMoRouters()
    {
        $list = $this->mtRouter->all();
        $this->assertNotEmpty($list);
        $row = array_shift($list);
        $this->assertArrayHasKey('order', $row);
        $this->assertInternalType('int', $row['order']);
        $this->assertArrayHasKey('rate', $row);
        $this->assertInternalType('float', $row['rate']);
        $this->assertArrayHasKey('type', $row);
        $this->assertArrayHasKey('connectors', $row);
        $this->assertInternalType('array', $row['connectors']);
        $this->assertArrayHasKey('filters', $row);
        $this->assertInternalType('array', $row['filters']);
    }
}
