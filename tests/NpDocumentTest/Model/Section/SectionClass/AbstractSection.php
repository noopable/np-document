<?php
namespace NpDocumentTest\Model\Section\SectionClass;

use NpDocument\Model\Section\SectionClass\Section;
use NpDocumentTest\Model\Section\SectionClass\TestAsset\DataContainer;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-08 at 22:06:20.
 */
abstract class AbstractSection extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Section
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Section;
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers NpDocument\Model\Section\SectionClass\Section::getDataContainer
     */
    public function testGetDataContainer()
    {
        //何もしなければデフォルトのDataContainerを取得できる
        $this->assertInstanceOf('NpDocument\Model\Section\DataContainer', $this->object->getDataContainer());
        
        //dataContainerプロパティに設定されたオブジェクトを取得できる
        $ref = new \ReflectionObject($this->object);
        $prop = $ref->getProperty('dataContainer');
        $prop->setAccessible(true);
        
        $customContainer = $this->getMock('Flower\Model\AbstractEntity');
        $prop->setValue($this->object, $customContainer);

        $this->assertSame($customContainer, $this->object->getDataContainer());
    }

    /**
     * 
     * @depends testGetDataContainer
     * @covers NpDocument\Model\Section\SectionClass\Section::setDataContainer
     */
    public function testSetDataContainer()
    {
        $customContainer = $this->getMock('Flower\Model\AbstractEntity');
        $this->object->setDataContainer($customContainer);
        $this->assertSame($customContainer, $this->object->getDataContainer());
    }
    
    /**
     * @depends testSetDataContainer
     * @covers NpDocument\Model\Section\SectionClass\Section::__isset
     */
    public function test__isset()
    {
        //not set property
        $this->assertFalse(isset($this->object->foo), 'normally isset');
        $this->assertFalse($this->object->__isset('foo'), 'no action has none');
        //without data container
        $this->object->foo = 'bar';
        $this->assertTrue(isset($this->object->foo), 'isset property');
        $this->assertTrue($this->object->__isset('foo'), 'object has property');
        
        //with data container late set
        $dataContainer = new DataContainer;
        $this->object->setDataContainer($dataContainer);
        $this->assertFalse(isset($this->object->foo), 'replaced data container lost previous index');
        $this->assertFalse($this->object->__isset('foo'), 'with replaced data container');
        
        //with data container having property
        $dataContainer->foo = 'bar';
        $this->assertTrue(isset($this->object->foo), 'data container having property1');
        $this->assertTrue($this->object->__isset('foo'), 'data container having property2');
        
    }
    
    /**
     * 
     * @depends test__isset
     * @covers NpDocument\Model\Section\SectionClass\Section::__get
     */
    public function test__get()
    {
        $this->object->foo = 'bar';
        //property access equals to __get
        
        $this->assertEquals('bar', $this->object->foo);
        $this->assertEquals('bar', $this->object->__get('foo'));
        
        $dataContainer = new DataContainer;
        $this->object->setDataContainer($dataContainer);
        $this->assertFalse(isset($this->object->foo));
        
        //property accesses dataContainer's property
        $dataContainer->foo = 'baz';
        $this->assertNotEquals('bar', $this->object->foo);
        $this->assertEquals('baz', $this->object->foo);
    }
    
    /**
     * @covers NpDocument\Model\Section\SectionClass\Section::__Set
     */
    public function test__set()
    {
        $dataContainer = $this->getMock('Flower\Model\AbstractEntity');
        $dataContainer->expects($this->once())
                ->method('offsetSet')
                ->with($this->equalTo('foo'), $this->equalTo('bar'));
        $this->object->setDataContainer($dataContainer);
        $this->object->foo = 'bar';
    }
}
