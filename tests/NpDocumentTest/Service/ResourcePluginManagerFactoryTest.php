<?php
namespace NpDocumentTest\Service;


use NpDocumentTest\Bootstrap;
use NpDocument\Service\ResourcePluginManagerFactory;
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-01 at 11:58:39.
 */
class PluginManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PluginManagerFactory
     */
    protected $object;
    
    /**
     *
     * @var ServiceManager;
     */
    protected $serviceManager;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        //$config = $serviceManager->get('Config');
        $this->object = new ResourcePluginManagerFactory;
        $ref = new \ReflectionObject($this->object);
        $prop = $ref->getProperty('configId');
        $prop->setAccessible(true);
        $prop->setValue($this->object, 'test_np_document_resources');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers NpDocument\Resource\PluginManagerFactory::createService
     */
    public function testCreateService()
    {
        $pluginManager = $this->object->createService($this->serviceManager);
        $this->assertInstanceOf('NpDocument\Resource\ResourcePluginManager', $pluginManager);
    }
    
    public function testConfigInjected()
    {
        $pluginManager = $this->object->createService($this->serviceManager);
        $resource = $pluginManager->get('test_generic');
        $this->assertInstanceOf('NpDocument\Resource\ResourceClass\ResourceInterface', $resource);
    }
    
}
