<?php

namespace Savvy\Component\Utility;

class IsoqueryTest extends \PHPUnit_Framework_TestCase
{
    private $testInstance;

    public function setup()
    {
        $this->testInstance = new Isoquery();
    }

    public function testObjectIsInstanceOfAbstractUtilityClass()
    {
        $this->assertInstanceOf('\Savvy\Component\Utility\Isoquery', $this->testInstance);
        $this->assertInstanceOf('\Savvy\Component\Utility\AbstractUtility', $this->testInstance);
    }

    public function testIsoquery()
    {
        $result = $this->testInstance->query('4217', 'USD');
        $this->assertEquals($result[1], 840);

        $result = $this->testInstance->query('3166', 'DEU');
        $this->assertEquals($result[2], 276);

        $result = $this->testInstance->query('3166', 'ThisDoesNotExist');
        $this->assertEquals(0, count($result));
    }
}
