<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class DemoController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }

    public function sidenavAction()
    {
        return new ViewModel;
    }
    
    public function whatsnewAction()
    {
        $sl = $this->getServiceLocator();
        if (! $sl->has('NpDocument_Repositories')) {
            $message = "NpDocument_Repositories not found";
            if (isset($this->logger)) {
                $this->logger->log($message);
            }
            else {
                trigger_error($message, E_USER_WARNING);
            }
            return array('error' => ['message' => $message]);
        }
        
        $repositoryManager = $sl->get('NpDocument_Repositories');
        
        $repository = $repositoryManager->byName('Document');

        $whatNew = $repository->getWhatsNew(6);

        return array('items' => $whatNew);
    }

}