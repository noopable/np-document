<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Section;

use Zend\ServiceManager\AbstractPluginManager;
use NpDocument\Model\Section\SectionInterface;
/**
 * Description of ResourcePluginManager
 *
 * @author tomoaki
 */
class SectionPluginManager extends AbstractPluginManager {

    protected $invokableClasses = array(
       'generic' => 'NpDocument\Model\Section\SectionClass\Section',
    );

    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $shareByDefault = false;
    /**
     * クラスを配置する namespace as prefix
     * 他の場所のクラスを使いたいときは、直接getで取得するか、
     * 同じnamespaceにプロキシを配置する。
     *
     * @var string
     */
    protected $pluginNameSpace = 'NpDocument\Model\Section\SectionClass';

    /**
     * ServiceLocatorなどの取得にグローバルServiceLocatorをpeeringして使う
     *
     * @var bool
     */
    protected $retrieveFromPeeringManagerFirst = false;

    public function setPluginNameSpace($pluginNameSpace)
    {
        $this->pluginNameSpace = (string) $pluginNameSpace;
    }

    public function getPluginNameSpace()
    {
        return $this->pluginNameSpace;
    }

    /**
     * Retrieve a service from the manager by name
     *
     * Allows passing an array of options to use when creating the instance.
     * createFromInvokable() will use these and pass them to the instance
     * constructor if not null and a non-empty array.
     *
     * @param  string $name
     * @param  array $options
     * @param  bool $usePeeringServiceManagers
     * @return object
     */
    public function get($name, $options = array(), $usePeeringServiceManagers = true)
    {
        // Allow specifying a class name directly; registers as an invokable class
        if (!$this->has($name) && $this->autoAddInvokableClass) {
            $this->autoAddInvokableClassByNamespace($name);
        }

        return parent::get($name, $options, $usePeeringServiceManagers);
    }

    public function autoAddInvokableClassByNamespace($name)
    {
        if (($pluginNameSpace = $this->getPluginNameSpace()) && (strpos($pluginNameSpace, $name) !== 0)) {
            $class = rtrim($pluginNameSpace, '\\') . '\\' . ucfirst($name);
            if (class_exists($class)) {
                $this->setInvokableClass($name, $class);
            }
        }
    }
    /**
     * Retrieve a service from the manager by name
     *
     * Allows passing an array of options to use when creating the instance.
     * createFromInvokable() will use these and pass them to the instance
     * constructor if not null and a non-empty array.
     *
     * @param  string $name
     * @param  array $options
     * @param  bool $usePeeringServiceManagers
     * @return object
     */
    public function byName($name, $options = array(), $usePeeringServiceManagers = true)
    {
        if (($pluginNameSpace = $this->getPluginNameSpace()) && (strpos($pluginNameSpace, $name) !== 0)) {
            $name = rtrim($pluginNameSpace, '\\') . '\\' . $name;
        }

        return parent::get($name, $options, $usePeeringServiceManagers);
    }

    public function validatePlugin($plugin) {
        if ($plugin instanceof SectionInterface) {
            return true;
        }
        return false;
    }

}
