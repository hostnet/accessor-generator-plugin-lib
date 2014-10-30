<?php

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

abstract class Boom {
    /**
     * Waarom dit dan?
     * @version bluh
     * @ORM\Column(name="stam", length=100, type="string")
     */
    private static $stam;

    use useless_trait;
    use another_useless_trait;
}
