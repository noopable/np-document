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
        foreach ($data as $key => $value) {
            //ものによってはsectionに編集を依頼するものもある。
            //必要に応じてセクションを新設することもある。
            //keyが_で分解できるなら、セクションEditを呼ぶ？
        }
        $this->document->addTask(AbstractDocument::TASK_SAVE);
        return $this;
    }

    public function get($branchId = null)
    {
        //Documentとsectionを合わせて、指定branchをKVに変換してセットデータを返す
    }

    public function _title($title)
    {
        //@todo base sectionのtitle属性を書き換える
        $sections = $this->document->getCurrentSections();
        //キーでbasesectionを特定できるかどうか。
        $baseSection = $sections['base'];
        //必要に応じてvalidationを行う?
        $baseSection->title = $title;

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this, '_' . $name)) {
            throw new RuntimeException('method can\'t call ' . $this->name . '::' . $name);
        }

        return call_user_func_array(array($this, '_' . $name), $arguments);
    }

}
