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
 * Configure Section object
 *
 * @author tomoaki
 */
class Config {

    /**
     *
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function configure(SectionInterface $section)
    {
        if (isset($this->config['data_container'])) {
            if (is_string($this->config['data_container']) && class_exists($this->config['data_container'])) {
                $dataContainer = new $this->config['data_container'];
            } else {
                $dataContainer = $this->config['data_container'];
            }

            if ($dataContainer instanceof AbstractEntity) {
                $section->setDataContainer($dataContainer);
            }
        }
    }
}
