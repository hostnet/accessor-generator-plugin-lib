<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity()
 */
class ContactInfo
{
    use Generated\ContactInfoMethodsTrait;

    /**
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @AG\Generate(get="private")
     */
    private $address_line;

    /**
     * @ORM\Column(type="string")
     * @AG\Generate(set="private")
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @AG\Generate(is="protected")
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean")
     * @AG\Generate(get="private", set="protected")
     */
    private $spends_lots_of_money;

    /**
     * @ORM\OneToMany(targetEntity="ContactInfo", mappedBy="referrer")
     * @AG\Generate(set="private")
     */
    private $referenced_contacts;

    /**
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="referenced_contacts")
     * @ORM\JoinColumn(name="referrer_id", referencedColumnName="id")
     * @AG\Generate(set="protected")
     */
    private $referrer;

    /**
     * @ORM\OneToMany(targetEntity="ContactInfo", mappedBy="friended_by")
     * @AG\Generate(add="private", remove="protected")
     */
    private $friends;

    /**
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="friends")
     * @ORM\JoinColumn(name="friended_id", referencedColumnName="id")
     * @AG\Generate(set="private")
     */
    private $friended_by;
}
