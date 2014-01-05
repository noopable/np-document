<?php
namespace NpDocument\Model\Sandbox;

use Flower\Model\AbstractEntity;

class Sandbox extends AbstractEntity
{
    protected $authenticated = false;

    protected $columns = array(
        'sandbox_id' => 'ID (ラベル的なもの)',
        'fullname' => 'お名前',
    );

    public function getIdentifier()
    {
        return array('sandbox_id');
    }

}