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
       //'generic' => 'NpDocument\Model\Section\SectionClass\Section',
    );
    
    /**
     * Whether or not to share by default
     *
     * @var bool
     */
    protected $shareByDefault = false;
    
    public function validatePlugin($plugin) {
        if ($plugin instanceof SectionInterface) {
            return true;
        }
        return false;
    }

}
