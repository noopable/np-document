<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NpDocument\Resource;

use Zend\ServiceManager\AbstractPluginManager;
use NpDocument\Resource\ResourceClass\ResourceInterface;
/**
 * Description of ResourcePluginManager
 *
 * @author tomoaki
 */
class ResourcePluginManager extends AbstractPluginManager {
    protected $invokableClasses = array(
        'standard' => 'NpDocument\Resource\ResourceClass\Resource',
    );
    
    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $shareByDefault = false;
    
    public function validatePlugin($plugin) {
        if ($plugin instanceof ResourceInterface) {
            return true;
        }
        return false;
    }

}
