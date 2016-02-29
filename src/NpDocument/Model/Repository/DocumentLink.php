<?php

/**
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Repository;

use Flower\Model\AbstractDbTableRepository;
use NpDocument\Model\Document\DocumentInterface;
use NpDocument\Model\DocumentLink as Link;
use NpDocument\Model\Exception\RuntimeException as ModelRuntimeException;
use Flower\Domain\DomainAwareInterface;
use Flower\Domain\DomainAwareTrait;
use Zend\Stdlib\ArrayUtils;

/**
 * Description of DocumentLink
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class DocumentLink extends AbstractDbTableRepository implements DomainAwareInterface
{
    use DomainAwareTrait;

    /**
     *
     * note: AbstractDocument has domain_id and document_id
     * @param \NpDocument\Model\Document\AbstractDocument $document
     */
    public function retrieveDocumentLinks(DocumentInterface $document)
    {
        $links = $this->getDocumentLinks($document->document_id, $document->domain_id);
        $document->setLinks($links);
        return $document;
    }

    public function getDocumentLinks($documentId, $domainId = null)
    {
        if (null === $domainId) {
            $domainId = $this->getDomainId();
        }
        $where = array(
            'domain_id' => $domainId,
            'document_id' => (int) $documentId,
        );
        $links = $this->getCollection($where);
        return ArrayUtils::iteratorToArray($links, false);
    }

    public function saveLinks(array $links)
    {
        foreach ($links as $link) {
            if ($link instanceof Link) {
                $this->saveLink($link);
            }
        }
    }

    public function saveLink(Link $link)
    {
        $link->hash = md5(uniqid(spl_object_hash($link)));
        $res = $this->save($link);
        $saved = $this->getEntity(['hash' => $link->hash]);
        //見つからない場合・・・
        if (! $saved) {
            throw new ModelRuntimeException('item lost at save process');
        }
        foreach ($saved as $key => $val) {
            $link->{$key} = $val;
        }
        return $res;
    }

    public function associateDocument(DocumentInterface $document, Link $link)
    {
        $link->domain_id = $document->domain_id;
        $link->document_id = $document->document_id;
        return $this;
    }
}
