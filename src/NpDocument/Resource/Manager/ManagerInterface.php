<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Resource\Manager;
/*
 * Resource feeds raw materials for document. 
 *  
 * Zend\Acl\Resourceとは違う、Documentの情報源としてのリソース
 * ファイルまたはRedisのようなKVSに保存する。
 * Zend\Cacheを利用してもよい。
 * 
 */
use NpDocument\Resource\ResourceClass\ResourceInterface;
/**
 *
 * ResourceProviderはエンティティそのものがリソースを提供できる場合の実装
 * Mapperはエンティティからリソースを提供
 * 
 * @author tomoaki
 */
interface ManagerInterface{
    
    public function save($entity = null);
    
    public function saveResource(ResourceInterface $resource);
    /**
     * リソースストレージからリソースを取り出す。
     * エンティティへのリバース用ではない。
     * 
     */
    public function get($resourceId, $refresh = false);

}
