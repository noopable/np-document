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
    const SECTION_REV_DELIMITER = '.';
    public function __construct(Config $config = null);
    public function setDataContainer(AbstractEntity $dataContainer);
    public function getDataContainer();
    public function getBranchSet($byArray = false);
}
