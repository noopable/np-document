<?php

namespace NpDocumentTest\Model\Domain;

use Flower\Test\TestTool;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-21 at 19:30:22.
 */
class ConcreteDomainAwareTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ConcreteDomainAware
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new TestAsset\ConcreteDomainAware;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers NpDocument\Model\Domain\DomainAwareTrait::setDomain
     */
    public function testSetDomain()
    {
        $domain = $this->getMock('NpDocument\Model\Domain\DomainInterface');
        $this->object->setDomain($domain);
        $this->assertEquals($domain, TestTool::getPropertyValue($this->object, 'domain'));
    }

    /**
     * @covers NpDocument\Model\Domain\DomainAwareTrait::getDomain
     * @todo   Implement testGetDomain().
     */
    public function testGetDomain()
    {
        $domain = $this->getMock('NpDocument\Model\Domain\DomainInterface');
        $this->object->setDomain($domain);
        $this->assertEquals($domain, $this->object->getDomain());
    }
}
