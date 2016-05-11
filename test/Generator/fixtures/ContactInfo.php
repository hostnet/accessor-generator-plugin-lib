<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;

/**
 * @ORM\Entity()
 * @method setName(string $name)
 * @method setFriendedBy(ContactInfo $contact_info)
 */
class ContactInfo
{
    use Generated\ContactInfoMethodsTrait;

    const GETTERS = [
        'getAddressLine',
        'getName',
        'isDeleted',
        'isSpendsLotsOfMoney',
        'getReferencedContacts',
        'getReferrer',
        'getFriends',
        'getFriendedBy',
    ];

    const SETTERS = [
        'setAddressLine',
        'setName',
        'setDeleted',
        'setSpendsLotsOfMoney',
        'setReferrer',
        'setFriendedBy',
    ];

    const ADDERS = [
        'addReferencedContact',
        'addFriend',
    ];

    const REMOVERS = [
        'removeReferencedContact',
        'removeFriend',
    ];

    /**
     * @ORM\Id
     * @ORM\Column
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @AG\Generate(get="private", strict=false)
     */
    private $address_line;

    /**
     * @ORM\Column(type="string")
     * @AG\Generate(set="private", strict=false)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @AG\Generate(is="protected", strict=false)
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean")
     * @AG\Generate(get="private", set="protected", strict=false)
     */
    private $spends_lots_of_money;

    /**
     * @ORM\OneToMany(targetEntity="ContactInfo", mappedBy="referrer")
     * @AG\Generate(set="private", strict=false)
     */
    private $referenced_contacts;

    /**
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="referenced_contacts")
     * @ORM\JoinColumn(name="referrer_id", referencedColumnName="id")
     * @AG\Generate(set="protected", strict=false)
     */
    private $referrer;

    /**
     * @ORM\OneToMany(targetEntity="ContactInfo", mappedBy="friended_by")
     * @AG\Generate(add="private", remove="protected", strict=false)
     */
    private $friends;

    /**
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="friends")
     * @ORM\JoinColumn(name="friended_id", referencedColumnName="id")
     * @AG\Generate(set="private", strict=false)
     */
    private $friended_by;

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this, $name], $arguments);
    }

    public function getAll()
    {
        $result = [];
        foreach (self::GETTERS as $method) {
            $result[$method] = call_user_func([$this, $method]);
        }
        return $result;
    }
}
