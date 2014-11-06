<?php
namespace Hostnet\Product\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

class Product
{
    /**
     * Product Id not good etc
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @AG\Generate
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Hostnet\Product\Entity\Period")
     * @ORM\JoinColumn(name="looptijd_id")
     * @AG\Generate(set=false)
     * @var Period
     */
    private $duration;

    /**
     * @ORM\Column(name="naam", type="string", length=50)
     * @AG\Generate(set=false)
     */
    private $name;

    /**
     * Used in invoices and email
     * @ORM\Column(name="omschrijving_factuur", type="string", length=50)
     * @AG\Generate(set=false)
     */
    private $description;

    /**
     * @ORM\Column(name="begin_datum", type="datetime")
     */
    private $start_date;

    /**
     * @ORM\Column(name="eind_datum", type="datetime")
     */
    private $end_date;

    /**
     * @ORM\Column(name="expensive", type="boolean")
     */
    private $expensive;

    /**
     * @ORM\Column(name="systeem_naam", type="string", length=50)
     * @AG\Generate
     */
    private $system_name;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Hostnet\Product\Entity\Attribute",
     *   mappedBy="product",
     *   cascade={"all"},
     *   orphanRemoval=true
     * )
     * @AG\Generate
     */
    private $attributes;
}
