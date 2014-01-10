<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Section;

use Zend\Stdlib\AbstractOptions;
/**
 * Configure Section object
 *
 * @author tomoaki
 */
class Config extends AbstractOptions {

    public function configure(SectionInterface $section)
    {
        if (isset($this->data_container)) {
            if ($this->data_container instanceof AbstractEntity) {
                $section->setDataContainer($this->data_container);
            } elseif (is_string($this->data_container) && class_exists($this->data_container)) {
                $section->setDataContainer(new $this->data_container);
            } 
        }
    }
}
