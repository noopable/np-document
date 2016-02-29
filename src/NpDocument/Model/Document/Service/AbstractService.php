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

    protected $name;

    public function __construct(DocumentInterface $document, $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }

        /**
         * AbstractDocument::__call側で自動処理するので、servicesに登録があれば、
         * この処理は実際には使われない可能性が高い。
         *
         * @var type
         */
        if (!isset($this->name)) {
            $names = explode('\\', get_class($this));
            $this->name = array_pop($names);
        }

        $this->document = $document;
        $document->setService($this->name, $this);
    }
}
