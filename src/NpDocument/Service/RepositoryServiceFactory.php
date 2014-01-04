<?php
namespace NpDocument\Service;

use Flower\Model\Service\RepositoryServiceFactory as AbstractRSF;
/**
 * Description of RepositoryServiceFactory
 *
 * @author tomoaki
 */
class RepositoryServiceFactory extends AbstractRSF {
    /**
     *
     * @var string
     */
    protected $configId = 'np-document_repositories';
    
    /**
     *
     * @var string
     */
    protected $managerClass = 'NpDocument\Service\RepositoryPluginManager';
    
    /**
     * whether or not use DependencyInjector
     * 
     * @var bool
     */
    protected $useDi = true;    
}
