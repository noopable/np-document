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
    /*
     * @see NpDocument\Model\Document\Service\Create
     */
    protected $defaultSectionsDef = array(
        // section_name => section_class | array section builddef for SectionPluginManager
        'body' => 'generic',
        /**
         * another?
         * acl
         * relay
         */
    );

    /**
     * 具体的に使用するスキーマに合わせるとよいと思います。
     *
     * @var type
     */
    protected $columns = array(
        'global_document_id' =>'global_document_id',
        'domain_id' =>'domain_id',
        'document_id' =>'document_id',
        'document_class' =>'document_class',
        'document_name' =>'document_name',
        'document_title' =>'document_title',
        'document_digest' =>'document_digest',
        'document_tag' =>'document_tag',
        'author' =>'author',
        'branch_set' =>'branch_set',
        'branch' =>'branch',
        'priority' =>'priority',
        'permission' =>'permission',
        'acl_resource_id' =>'acl_resource_id',
        'published' =>'published',
        'lastupdated' =>'lastupdated',
        'object_hash' =>'object_hash',
        //'links' => 'links',//populateを再帰実装してもよい。　DBupdate時は問題になる。
        //'sections' => 'sections',//populateを再帰実装してもよい。
    );
}