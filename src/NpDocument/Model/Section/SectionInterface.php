<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Section;

use Flower\Model\AbstractEntity;
/**
 *
 * @author tomoaki
 */
interface SectionInterface {
    public function setDataContainer(AbstractEntity $dataContainer);
    public function getDataContainer();
}
