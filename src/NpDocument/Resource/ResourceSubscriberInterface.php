<?php
/*
 *
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */
namespace NpDocument\Resource;
/*
 * Subscriber watches resource changes as Resource Consumer.
 * Consumer needs resources for any purpose
 *  
 * 
 */

use NpDocument\Resource\ResourceClass\ResourceInterface;
/**
 *
 * @author tomoaki
 */
interface ResourceSubscriberInterface {
    
    public function onResourceUpdate(ResourceInterface $resource);
    public function uses();
    
}
