<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document;

use NpDocument\Model\Section\SectionInterface;

/**
 *
 * @author tomoaki
 */
interface DocumentInterface {
    /**
     * @see data/resource/document_before_insert.trigger
     */
    const GLOBAL_DOCUMENT_DELIMITER = '-';

    const TASK_SAVE = 'save';

    const TASK_PUBLISH = 'publish';

    public function getGlobalDocumentId();

    public function addSection(SectionInterface $section);

    public function setSections(array $sections);

    public function getSections();

    public function setLinks(array $links);

    public function getLinks();

    public function setCurrentBranchId($branchId);

    public function getCurrentBranchId();

    public function updateCurrentSections();

    public function getCurrentSections();
        /**
     * remove hash key and duplicate entry
     * @return type
     */
    public function getTasks();

    public function addTask($task);

    public function removeTask($task);

}
