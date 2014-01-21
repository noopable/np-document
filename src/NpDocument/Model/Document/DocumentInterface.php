<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document;

/**
 *
 * @author tomoaki
 */
interface DocumentInterface {
    /**
     * @see data/resource/document_before_insert.trigger
     */
    const GLOBAL_DOCUMENT_DELIMITER = '-';
    
    public function getGlobalDocumentId();
    
    public function setSections(array $sections);
    
    public function getSections();
}
