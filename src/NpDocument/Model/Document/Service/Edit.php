<?php

/*
 *
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Document\Service;

use NpDocument\Model\Document\AbstractDocument;
/**
 * Model\Document
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
class Edit extends AbstractService {

    public function set(array $data)
    {
        foreach ($data as $k => $v) {
            if (is_string($k)) {
                //offsetSetを通すため
                $this->document->{$k} = $v;
            }
        }
        $this->document->addTask(AbstractDocument::TASK_SAVE);
        return $this;
    }

    public function get($branchId = null)
    {
        //Documentとsectionを合わせて、指定branchをKVに変換してセットデータを返す
    }
}
