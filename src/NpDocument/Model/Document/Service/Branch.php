<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

/**
 * Model\Document
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class Branch extends AbstractService {

    public function publish($branchId = null, $time = null)
    {
        $document = $this->document;
        if (null === $branchId) {
            $branchId = $document->getCurrentBranchId();
        }
        $document->branch = $branchId;
        if (null === $document->published) {
            $document->setPublished($time);
        }
        $sections = $document->getSections();
        foreach ($sections as $section) {
            if (in_array($branchId, $section->getBranchSet(true))) {
                $section->release_tag = 'master';
            } else {
                $section->release_tag = '';
            }
        }
        $document->addTask(AbstractDocument::TASK_SAVE);
        $document->addTask(AbstractDocument::TASK_PUBLISH);
    }

    public function checkout($branchId)
    {
        $this->document->setCurrentBranchId($branchId);
    }

    /**
     * $branchIdが指定されていればそのブランチを元に新しいブランチを作る
     * $branchIdが指定されていなければ、メインブランチを元に新しいブランチを作る
     *
     * @param int $parentBranchId
     */
    public function create($parentBranchId = null)
    {

    }

    /**
     * ブランチIDを指定してブランチを削除する。
     * @param type $branchId
     */
    public function remove($branchId)
    {
        //update these
        //document->branch_set
        //section->
    }

}
