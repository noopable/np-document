<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

use NpDocument\Model\Document\AbstractDocument;
use NpDocument\Model\Repository\Section as SectionRepository;

/**
 * Model\Document
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class Create extends AbstractService {

    protected $repository;

    public function init($repository)
    {
        $this->repository = $repository;
        $this->_init();
    }

    protected function _init()
    {
        if ($this->document instanceof AbstractDocument) {
            $sectionsDef = $this->document->getDefaultSectionsDef();
            $sectionRepository = $this->repository->getSectionRepository();
            if ($sectionRepository instanceof SectionRepository) {
                //標準的なセクションを追加する
                foreach ($sectionsDef as $sectionName => $def) {
                    $type = 'generic';
                    $params = array();
                    if (is_array($def)) {
                        $params = $def;
                    } elseif (is_string($def)) {
                        $type = $def;
                    }
                    $section = $sectionRepository->createSection($type, $params);
                    $section->section_name  = $sectionName;
                    //保存するまでdocument_idは育成されない。
                    $this->document->addSection($section);
                    //$sectionRepository->associateDocument($this->document, $section);
                }
            }
        }

    }

    public function set(array $data)
    {
        foreach ($data as $k => $v) {
            if (is_string($k)) {
                //offsetSetを通すため
                $this->document->{$k} = $v;
            }
        }
        $this->document->addTask(AbstractDocument::TASK_SAVE);
        return $this;
    }

    public function title($title)
    {
        //@todo base sectionのtitle属性を書き換える
        $sections = $this->document->getCurrentSections();
        //キーでbasesectionを特定できるかどうか。
        $baseSection = $sections['base'];
        //必要に応じてvalidationを行う?
        $baseSection->title = $title;

        return $this;
    }

}
