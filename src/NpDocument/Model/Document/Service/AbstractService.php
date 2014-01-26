<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

use NpDocument\Model\Document\DocumentInterface;
/**
 * AbstractService is a part of the document
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
abstract class AbstractService {
    protected $document;

    protected $name = 'Abstract';

    public function __construct(DocumentInterface $document, $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }

        $this->document = $document;
        $document->setService($this->name, $this);
    }
}
