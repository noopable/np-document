<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Repository;

use Flower\Model\Service\RepositoryPluginManager as AbstractRepositoryPluginManager;
/**
 * Description of RepositoryPluginManager
 *
 * @author tomoaki
 */
class RepositoryPluginManager extends AbstractRepositoryPluginManager {
    
    /**
     * クラスを配置する namespace as prefix
     * 他の場所のクラスを使いたいときは、直接getで取得するか、
     * 同じnamespaceにプロキシを配置する。
     * 
     * @var string 
     */
    protected $pluginNameSpace = 'NpDocument\Model\Repository';
    
    public function loadRepository($name, array $params = array())
    {
        //$repository = $this->getServiceLocator()->get('Di')->get($name, $params);

        //$repository->initialize($this);
        //initializeはinitializerが処理してくれる。
        return $this->byName($name, $params);
        //return $repository;
    }

}
