<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

use NpDocument\Model\Document\DocumentInterface;
/**
 * Description of AbstractService
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
abstract class AbstractService {
    protected $document;

    protected $name = 'Abstract';
    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
        $document->addService($this->name, $this);
    }
}
