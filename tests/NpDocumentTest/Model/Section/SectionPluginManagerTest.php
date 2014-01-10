<?php
namespace NpDocumentTest\Model\Section;

use NpDocument\Model\Section\Config;
use NpDocument\Model\Section\DataContainer;
use NpDocument\Model\Section\SectionClass\Section;
use NpDocument\Model\Section\SectionPluginManager;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-08 at 21:37:28.
 */
class SectionPluginManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SectionPluginManager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SectionPluginManager;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers NpDocument\Model\Section\SectionPluginManager::setPluginNameSpace
     */
    public function testSetPluginNameSpace()
    {
        $ref = new \ReflectionObject($this->object);
        $prop = $ref->getProperty('pluginNameSpace');
        $prop->setAccessible(true);
        $this->object->setPluginNameSpace('NpDocumentTest\Foo');
        $this->assertEquals('NpDocumentTest\Foo', $prop->getValue($this->object));
    }

    /**
     * @depends testSetPluginNameSpace
     * @covers NpDocument\Model\Section\SectionPluginManager::getPluginNameSpace
     */
    public function testGetPluginNameSpace()
    {
        $this->assertEquals('NpDocument\Model\Section\SectionClass', $this->object->getPluginNameSpace());
        $this->object->setPluginNameSpace('NpDocumentTest\Foo');
        $this->assertEquals('NpDocumentTest\Foo', $this->object->getPluginNameSpace());
    }

    /**
     * @covers NpDocument\Model\Section\SectionPluginManager::byName
     */
    public function testByName()
    {
        $section = $this->object->byName('Section');
        $this->assertInstanceOf('NpDocument\Model\Section\SectionClass\Section', $section);
    }
    
    /**
     * @covers NpDocument\Model\Section\SectionPluginManager::get
     */
    public function testGet()
    {
        $section = $this->object->get('Section');
        $this->assertInstanceOf('NpDocument\Model\Section\SectionClass\Section', $section);
    }
    
    /**
     * @covers NpDocument\Model\Section\SectionPluginManager::validatePlugin
     */
    public function testValidatePlugin()
    {
        $instance = new Section;
        $this->assertTrue($this->object->validatePlugin($instance));
        $notImplements = new \stdClass;
        $this->assertFalse($this->object->validatePlugin($notImplements));
    }
    
    /**
     * 
     * @expectedException Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNoConfigGet()
    {
        $this->object->get('generic');
    }
    
    public function testGetWithParams()
    {
        $dataContainer = new DataContainer;
        $config = array(
            'data_container' => $dataContainer,
        );
        $params = new Config($config);
        $this->object->setInvokableClass('generic', 'NpDocument\Model\Section\SectionClass\Section');
        $section = $this->object->get('generic', $params);
        $this->assertInstanceOf('NpDocument\Model\Section\SectionInterface', $section);
        $injectedDataContainer = $section->getDataContainer();
        $this->assertSame($dataContainer, $injectedDataContainer);
    }
    
    public function testByDiGet()
    {
        $config = array(
            'instance' => array(
                'NpDocument\Model\Section\SectionPluginManager' => array(
                    'parameters' => array(

                    ),
                    'injections' => array(
                        'setInvokableClass' => array(
                            array('name' => 'generic', 'invokableClass' => 'NpDocument\Model\Section\SectionClass\Section'),
                        ),
                    ),
                ),
            ),
        );
        $oConfig = new \Zend\Di\Config($config);
        $di = new \Zend\Di\Di;
        $oConfig->configure($di);
        
        $plugin = $di->get('NpDocument\Model\Section\SectionPluginManager');
        $this->assertInstanceOf('NpDocument\Model\Section\SectionPluginManager', $plugin);
        $instance = $plugin->get('generic');
        $this->assertInstanceOf('NpDocument\Model\Section\SectionClass\Section', $instance);
        return $plugin;
    }
    
    /**
     * @depends testByDiGet
     */
    public function testByDiGetWithParams($plugin)
    {
        $dataContainer = new DataContainer;
        $config = array(
            'data_container' => $dataContainer,
        );
        $params = new Config($config);
        $section = $plugin->get('generic', $params);
        $this->assertInstanceOf('NpDocument\Model\Section\SectionInterface', $section);
        $injectedDataContainer = $section->getDataContainer();
        $this->assertSame($dataContainer, $injectedDataContainer);
    }
}
