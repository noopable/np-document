<?php

/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Section;

use Flower\Model\AbstractEntity;

class Section extends AbstractEntity implements SectionInterface
{
    protected $authenticated = false;

    public function getIdentifier()
    {
        return array('domain_id', 'document_id', 'section_name', 'section_rev');
    }
}