<?php

namespace NpDocumentTest\Model\Section\SectionClass\TestAsset;

use Flower\Model\AbstractEntity;

/**
 * Description of DataContainer
 *
 * @author tomoaki
 */
class DataContainer extends AbstractEntity {
    
    public function getIdentifier()
    {
        return array('dummy_id');
    }
}
