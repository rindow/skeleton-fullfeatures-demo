<?php
namespace Acme\MyApp\Tests\Controller\IndexControllerTest;

use Acme\MyApp\Tests\Tools\AbstractTestCase;

class Test extends AbstractTestCase
{
    public function testIndexEmpty()
	{
        $this->dispatch('/');
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('Welcome to Rindow'));
    }

    public function testIndexColor()
	{
        $this->dispatch('/color/Red');
        $this->assertEquals(200,$this->statusCode());
        $this->assertTrue($this->match('gradient-red'));
    }
}
