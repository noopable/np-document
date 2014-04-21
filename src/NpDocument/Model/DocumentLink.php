<?php

/**
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model;

use Flower\Model\AbstractEntity;

/**
 * Description of DocumentLink
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class DocumentLink extends AbstractEntity
{
    public function getIdentifier()
    {
        return array('link_id');
    }

    public function setRouteParams(array $routeParams)
    {
        $this->route_params = '';
        foreach ($routeParams as $key => $value) {
            $this->route_params .= $key . ' ' . $value . "\n";//expects CRLF is convert to LF in DB trigger
        }

        return $this;
    }

    public function getRouteParams($document = null)
    {
        $lines = explode("\n", $this->route_params);
        if (! $lines) {
            return array();
        }
        $routeParams = array();
        foreach ($lines as $line) {
            $entry = explode(' ', $line, 2);
            if (count($entry) === 2) {
                $routeParams[$entry[0]] = $this->parseRouteParam($entry[1], $document);
            }
        }
        return $routeParams;
    }

    public function parseRouteParam($field, $document = null)
    {
        if (null === $document) {
            $document = $this;
        }
        $matches = array();
        if (preg_match_all('/\?(.*?)\?/i', $field, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (isset($document->{$match[1]})) {
                    $field = preg_replace('/' . preg_quote($match[0]) . '/i', $document->{$match[1]}, $field);
                }
            }
        }

        return $field;
    }
}
