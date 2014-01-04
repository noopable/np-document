<?php
namespace NpDocument\Service;

/**
 * Description of RepositoryPluginManager
 *
 * @author tomoaki
 */
class RepositoryPluginManager extends \Flower\Model\Service\RepositoryPluginManager {
    
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
