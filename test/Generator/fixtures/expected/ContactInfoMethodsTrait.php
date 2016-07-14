<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Collection\ImmutableCollection;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\ContactInfo;

trait ContactInfoMethodsTrait
{
    /**
     * Get address_line
     *
     * @return string|null
     * @throws \InvalidArgumentException
     */
    private function getAddressLine()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getAddressLine() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->address_line === null) {
            return null;
        }

        return $this->address_line;
    }

    /**
     * Set address_line
     *
     * @param string $address_line
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setAddressLine($address_line)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setAddressLine() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($address_line === null
            || is_scalar($address_line)
            || is_callable([$address_line, '__toString'])
        ) {
            $address_line = (string)$address_line;
        } else {
            throw new \InvalidArgumentException(
                'Parameter address_line must be convertable to string.'
            );
        }

        $this->address_line = $address_line;
        return $this;
    }

    /**
     * Get name
     *
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getName()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getName() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->name === null) {
            return null;
        }

        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    private function setName($name)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setName() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($name === null
            || is_scalar($name)
            || is_callable([$name, '__toString'])
        ) {
            $name = (string)$name;
        } else {
            throw new \InvalidArgumentException(
                'Parameter name must be convertable to string.'
            );
        }

        $this->name = $name;
        return $this;
    }

    /**
     * Is deleted
     *
     * @return boolean|null
     * @throws \InvalidArgumentException
     */
    protected function isDeleted()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'isDeleted() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->deleted === null) {
            return null;
        }

        return $this->deleted;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setDeleted($deleted)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDeleted() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_bool($deleted)) {
            throw new \InvalidArgumentException(
                'Parameter deleted must be boolean.'
            );
        }

        $this->deleted = $deleted;
        return $this;
    }

    /**
     * Is spends_lots_of_money
     *
     * @return boolean|null
     * @throws \InvalidArgumentException
     */
    private function isSpendsLotsOfMoney()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'isSpendsLotsOfMoney() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->spends_lots_of_money === null) {
            return null;
        }

        return $this->spends_lots_of_money;
    }

    /**
     * Set spends_lots_of_money
     *
     * @param boolean $spends_lots_of_money
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    protected function setSpendsLotsOfMoney($spends_lots_of_money)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSpendsLotsOfMoney() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (!is_bool($spends_lots_of_money)) {
            throw new \InvalidArgumentException(
                'Parameter spends_lots_of_money must be boolean.'
            );
        }

        $this->spends_lots_of_money = $spends_lots_of_money;
        return $this;
    }

    /**
     * Get referenced_contacts
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\ContactInfo[]
     * @return \Hostnet\Component\AccessorGenerator\Collection\ConstCollectionInterface
     * @throws \InvalidArgumentException
     */
    public function getReferencedContacts()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getReferencedContacts() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->referenced_contacts === null) {
            $this->referenced_contacts = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->referenced_contacts);
    }

    /**
     * Add referenced_contact
     *
     * @param ContactInfo $referenced_contact
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    private function addReferencedContact(ContactInfo $referenced_contact)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addReferencedContacts() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->referenced_contacts === null) {
            $this->referenced_contacts = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->referenced_contacts->contains($referenced_contact)) {
            return $this;
        }

        $this->referenced_contacts->add($referenced_contact);
        try {
            $property = new \ReflectionProperty($referenced_contact, 'referrer');
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        $value = $property->getValue($referenced_contact);
        if ($value && $value !== $this) {
            throw new \LogicException('ReferencedContact can not be added to more than one ContactInfo.');
        }
        $property->setValue($referenced_contact, $this);
        $property->setAccessible(false);
        return $this;
    }

    /**
     * Remove referenced_contact
     *
     * @param ContactInfo $referenced_contact
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    private function removeReferencedContact(ContactInfo $referenced_contact)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeReferencedContacts() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->referenced_contacts instanceof \Doctrine\Common\Collections\Collection
            || ! $this->referenced_contacts->contains($referenced_contact)
        ) {
            return $this;
        }

        $this->referenced_contacts->removeElement($referenced_contact);

        $property = new \ReflectionProperty(ContactInfo::class, 'referrer');
        $property->setAccessible(true);
        $property->setValue($referenced_contact, null);
        $property->setAccessible(false);
        return $this;
    }

    /**
     * Get referrer
     *
     * @return ContactInfo|null
     * @throws \InvalidArgumentException
     */
    public function getReferrer()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getReferrer() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->referrer;
    }

    /**
     * Set referrer
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @param ContactInfo $referrer
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    protected function setReferrer(ContactInfo $referrer = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setReferrer() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }
        // Create reflection property.
        $property = new \ReflectionProperty(ContactInfo::class, 'referenced_contacts');
        $property->setAccessible(true);

        // Unset old value and set the new value
        if ($this->referrer) {
            $value = $property->getValue($this->referrer);
            $value && $value->removeElement($this);
        }

        // keeping the inverse side up-to-date.
        if ($referrer) {
            $value = $property->getValue($referrer);
            if ($value) {
                $referrer && $value->add($this);
            } else {
                $property->setValue($referrer, new \Doctrine\Common\Collections\ArrayCollection([$this]));
            }
        }

        // Disallow acces again.
        $property->setAccessible(false);

        $this->referrer = $referrer;
        return $this;
    }

    /**
     * Get friends
     *
     * @return \Hostnet\Component\AccessorGenerator\Generator\fixtures\ContactInfo[]
     * @return \Hostnet\Component\AccessorGenerator\Collection\ConstCollectionInterface
     * @throws \InvalidArgumentException
     */
    public function getFriends()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getFriends() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->friends === null) {
            $this->friends = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return new ImmutableCollection($this->friends);
    }

    /**
     * Add friend
     *
     * @param ContactInfo $friend
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    private function addFriend(ContactInfo $friend)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'addFriends() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->friends === null) {
            $this->friends = new \Doctrine\Common\Collections\ArrayCollection();
        } elseif ($this->friends->contains($friend)) {
            return $this;
        }

        $this->friends->add($friend);
        try {
            $property = new \ReflectionProperty($friend, 'friended_by');
        } catch (\ReflectionException $e) {
            throw new \Hostnet\Component\AccessorGenerator\Exception\MissingPropertyException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
        $property->setAccessible(true);
        $value = $property->getValue($friend);
        if ($value && $value !== $this) {
            throw new \LogicException('Friend can not be added to more than one ContactInfo.');
        }
        $property->setValue($friend, $this);
        $property->setAccessible(false);
        return $this;
    }

    /**
     * Remove friend
     *
     * @param ContactInfo $friend
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    protected function removeFriend(ContactInfo $friend)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'removeFriends() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if (! $this->friends instanceof \Doctrine\Common\Collections\Collection
            || ! $this->friends->contains($friend)
        ) {
            return $this;
        }

        $this->friends->removeElement($friend);

        $property = new \ReflectionProperty(ContactInfo::class, 'friended_by');
        $property->setAccessible(true);
        $property->setValue($friend, null);
        $property->setAccessible(false);
        return $this;
    }

    /**
     * Get friended_by
     *
     * @return ContactInfo|null
     * @throws \InvalidArgumentException
     */
    public function getFriendedBy()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getFriendedBy() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->friended_by;
    }

    /**
     * Set friended_by
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @param ContactInfo $friended_by
     * @return ContactInfo
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    private function setFriendedBy(ContactInfo $friended_by = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setFriendedBy() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }
        // Create reflection property.
        $property = new \ReflectionProperty(ContactInfo::class, 'friends');
        $property->setAccessible(true);

        // Unset old value and set the new value
        if ($this->friended_by) {
            $value = $property->getValue($this->friended_by);
            $value && $value->removeElement($this);
        }

        // keeping the inverse side up-to-date.
        if ($friended_by) {
            $value = $property->getValue($friended_by);
            if ($value) {
                $friended_by && $value->add($this);
            } else {
                $property->setValue($friended_by, new \Doctrine\Common\Collections\ArrayCollection([$this]));
            }
        }

        // Disallow acces again.
        $property->setAccessible(false);

        $this->friended_by = $friended_by;
        return $this;
    }
}
