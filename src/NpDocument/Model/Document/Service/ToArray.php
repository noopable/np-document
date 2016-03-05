<?php

/**
 *
 * @copyright Copyright (c) 2013-2016 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

use NpDocument\Model\Document\DocumentInterface;
use Zend\Stdlib\Hydrator\ObjectProperty;

/**
 * Description of ToArray
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class ToArray extends AbstractService
{
    static $hydrator;

    public function extract()
    {
        return static::docToArray($this->document);
    }

    protected static function getHydrator()
    {
        if (!isset(self::$hydrator)) {
            self::$hydrator = new ObjectProperty;
        }
        return self::$hydrator;
    }

    public static function docToArray(DocumentInterface $document)
    {
        $hydrator = static::getHydrator();
        $data = $hydrator->extract($document);
        $sections = $document->getSections();
        $data['sections'] = array_map([$hydrator, 'extract'], $sections);
        $links = $document->getLinks();
        $data['links'] = array_map([$hydrator, 'extract'], $links);
        return $data;
    }
}
