<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Document;

use Flower\Model\AbstractEntity;
use Flower\TimeUtils;
use NpDocument\Model\Exception\DomainException;
use NpDocument\Model\Exception\RuntimeException;
use NpDocument\Model\Document\DocumentInterface;

/**
 *
 *
 */
abstract class AbstractDocument extends AbstractEntity implements DocumentInterface
{

    protected $defaultSectionsDef;

    protected $services = array(
        'branch' => 'NpDocument\Model\Document\Service\Branch',
        'revision' => 'NpDocument\Model\Document\Service\Revision',
    );

    protected $sections;

    protected $links = array();

    protected $currentBranchId;

    protected $currentSections;

    protected $tasks = array();

    public function getIdentifier()
    {
        return array('domain_id', 'document_id');
    }

    public function getGlobalDocumentId()
    {
        if (!isset($this->global_document_id)) {
            if (isset($this->domain_id) && $this->document_id) {
                $this->global_document_id = self::generateGlobalDocumentId($this->domain_id, $this->document_id);
            } else {
                throw new DomainException('This document has no identity');
            }
        }
        return $this->global_document_id;
    }
    /**
     * @todo use Validator with DI
     * @todo remove static method from standard class, use abstract or another
     *
     * @see data/resource/document_before_insert.trigger
     *
     * @param integer $domainId
     * @param integer $documentId
     */
    public static function generateGlobalDocumentId($domainId, $documentId)
    {
        if (!is_int($domainId) || $domainId <= 0) {
            throw new DomainException('domain_id is invalid it should be unsigned integer');
        }

        if (!is_int($documentId) || $documentId <= 0) {
            throw new DomainException('document_id is invalid it should be unsigned integer');
        }

        if ($domainId > hexdec('FFFFFF')) {
            throw new DomainException('domain_id is too large');
        }

        if ($documentId > hexdec('FFFFFF')) {
            throw new DomainException('document_id is too large');
        }
        return sprintf('%06X' . DocumentInterface::GLOBAL_DOCUMENT_DELIMITER . '%06X', $domainId, $documentId);

    }

    /**
     *
     */
    public function getDefaultSectionsDef()
    {
        return $this->defaultSectionsDef;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function setSections(array $sections)
    {
        $this->sections = $sections;
    }

    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getLinkWithUrlHelper($urlHelper, $default = null)
    {
        $links = $this->getLinks();
        foreach ($links as $li) {
            $route = null;
            $routeParams = array();
            if (isset($li->route) || isset($li->route_params)) {
                $route = isset($li->route) ? $li->route : 'public';
                $routeParams = $li->getRouteParams($entry);
            } else {
                continue;
            }
            switch ($li->status) {
                case 'current':
                    return $this->url()->fromRoute($route, $routeParams);
                case 'draft':
                    if (isset($tmpLink)) {
                        break;
                    }
                    $tmpLink = $this->url()->fromRoute($route, $routeParams);
                    break;
                default:
                    if (isset($overdue)) {
                        break;
                    }
                    $overdue = $this->url()->fromRoute($route, $routeParams);
                    break;
            }
        }
        if (isset($tmpLink)) {
            return $tmpLink;
        }

        if (isset($overdue)) {
            return $overdue;
        }

        return $default;
    }

    /**
     *
     * @param string $name
     * @param \NpDocument\Model\Document\Service\AbstractService $service
     */
    public function setService($name, $service)
    {
        $this->services[$name] = $service;
    }

    public function removeService($name)
    {
        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
    }

    public function setPublished($time = null)
    {
        $this->published = TimeUtils::mysqlFormatDatetime($time);
    }

    /**
     * remove hash key and duplicate entry
     * @return type
     */
    public function getTasks()
    {
        return array_flip(array_flip($this->tasks));
    }

    public function addTask($task)
    {
        $this->tasks[] = $task;
    }

    public function removeTask($task)
    {
        $taskFlip = array_flip($this->tasks);
        if (isset($taskFlip[$task])) {
            unset($taskFlip[$task]);
        }
        $this->tasks = array_flip($taskFlip);
    }

    public function setCurrentBranchId($branchId)
    {
        $this->currentBranchId = $branchId;
        $this->updateCurrentSections();
    }

    public function getCurrentBranchId()
    {
        return $this->currentBranchId;
    }

    public function updateCurrentSections()
    {
        if (!isset($this->sections)) {
            return;
        }

        $branchId = $this->getCurrentBranchId();
        if (null === $branchId) {
            $this->currentSections = $this->sections;
        }

        $this->currentSections = array();
        foreach ($this->sections as $key => $section) {
            if (in_array($branchId, $section->getBranchSet(true))) {
                $this->currentSections[$key] = $section;
            }
        }
    }

    public function getCurrentSections()
    {
        return $this->currentSections;
    }

    public function __call($name, $arguments)
    {
        if (! ($pos = strpos($name, '_')) > 0 ) {
            throw new RuntimeException('undefined method:' . $name);
        }

        list($serviceName, $methodName) = array_merge(explode('_', $name, 2), array(''));
        if (! isset($this->services[$serviceName])) {
            throw new RuntimeException('undefined service:'. $serviceName . ' with method :' . $name);
        }

        $service = $this->services[$serviceName];

        if (is_string($service)) {
            if (! class_exists($service)) {
                throw new RuntimeException('specified class name (' . $service . ') is not found');
            }
            // prefer compatible constructor style with AbstractService
            try {
                //flyweight is AbstractService's task.
                //  - Service can omit setService
                //  - We can set service with servicename from external scope.
                $service = new $service($this, $serviceName);
            } catch (\Exception $ex) {
                throw new RuntimeException('prefer service constructor to have compatile with AbstractService. Or you can set instance directory with setService', $ex->getCode(), $ex);
            }
        }

        if (! is_object($service)) {
            throw new RuntimeException('unknown service type :' . gettype($service));
        }

        if (! method_exists($service, $methodName)) {
            throw new RuntimeException('method can\'t call ' . $name);
        }
        // at last, invoke
        return call_user_func_array(array($service, $methodName), $arguments);
    }
}