<?php

/*
 * 
 * @copyright Copyright (c) 2013-2014 KipsProduction (http://www.kips.gr.jp)
 * @license   http://www.kips.gr.jp/newbsd/LICENSE.txt New BSD License
 */

namespace NpDocument\Model\Domain;

/**
 *
 * @author Tomoaki Kosugi <kosugi at kips.gr.jp>
 */
interface DomainAwareInterface {
    public function setDomain(DomainInterface $domain);
    public function getDomain();
}
