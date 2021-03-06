<?php
namespace NpDocument\Model\Document\DocumentClass;

use Flower\Test\TestTool;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-21 at 19:11:08.
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

    public function testConcrete()
    {
        $expectedDraft = array(
            // section_name => section_class | array section builddef for SectionPluginManager
            'digest' => 'generic',
            'body' => 'generic',
        );
        $this->assertEquals($expectedDraft, TestTool::getPropertyValue($this->object, 'defaultSectionsDef'));
    }
}
