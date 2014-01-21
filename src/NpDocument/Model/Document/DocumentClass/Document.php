<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Model\Document\DocumentClass;

use NpDocument\Model\Document\AbstractDocument;

/**
 * 
 */
class Document extends AbstractDocument
{
    protected $defaultSectionsDef = array(
        // section_name => section_class | array section builddef for SectionPluginManager
        'base' => 'Base', 
        'tag' => array(
            'section_class' => 'Tag',
        ),
    );
}