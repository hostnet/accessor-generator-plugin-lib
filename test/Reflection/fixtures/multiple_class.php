<?php

use Doctrine\ORM\Mapping as ORM;
abstract class Boom {
    /**
     * Waarom dit dan?
     *
     * en dit dan?
     * @version bluh
     * @ORM\Column(name="stam", length=100, type="string")
     */
    private $stam;

    use useless_trait;
}
