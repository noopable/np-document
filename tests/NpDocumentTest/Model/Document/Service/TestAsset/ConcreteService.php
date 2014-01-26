<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocumentTest\Model\Document\Service\TestAsset;

use NpDocument\Model\Document\Service\AbstractService;
/**
 * Description of ConcreteService
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class ConcreteService extends AbstractService {
    public function doSomething()
    {
        return func_get_args();
    }
}
