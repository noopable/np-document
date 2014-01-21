<?php
namespace NpDocument\Model\Document;

use NpDocument\Model\Document\DocumentClass\Document;
use Flower\Test\TestTool;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-21 at 19:07:32.
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Document
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Document;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers NpDocument\Model\Document\AbstractDocument::getIdentifier
     * @todo   Implement testGetIdentifier().
     */
    public function testGetIdentifier()
    {
        $expected = array('domain_id', 'document_id');
        $this->assertEquals($expected, $this->object->getIdentifier());
    }

    /**
     * @covers NpDocument\Model\Document\AbstractDocument::getGlobalDocumentId
     */
    public function testGetGlobalDocumentId()
    {
        $this->object->domain_id = 1235;
        $this->object->document_id = 123;
        $this->assertEquals('0004D3-00007B', $this->object->getGlobalDocumentId());
    }

    /**
     * @covers NpDocument\Model\Document\AbstractDocument::getDefaultSectionsDef
     */
    public function testGetDefaultSectionsDef()
    {
        $def = array('foo' => 'bar');
        $ref = new \ReflectionObject($this->object);
        $prop = $ref->getProperty('defaultSectionsDef');
        $prop->setAccessible(true);
        $prop->setValue($this->object, $def);
        $this->assertEquals($def, $this->object->getDefaultSectionsDef());
    }

    /**
     * @covers NpDocument\Model\Document\AbstractDocument::setSections
     */
    public function testSetSections()
    {
        $sections = array('foo' => 'bar');
        $this->object->setSections($sections);
        $this->assertEquals($sections, TestTool::getPropertyValue($this->object, 'sections'));
    }
    
    /**
     * @depends testSetSections
     * @covers NpDocument\Model\Document\AbstractDocument::getSections
     */
    public function testGetSections()
    {
        $sections = array('foo' => 'bar');
        $this->object->setSections($sections);
        $this->assertEquals($sections, $this->object->getSections());
    }


}
