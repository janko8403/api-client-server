<?php

namespace Customers\Entity;

/**
 * UserRelationJoint
 */
class UserRelationJoint
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \Customers\Entity\Customer
     */
    private $customer;

    /**
     * @var \Users\Entity\User
     */
    private $user;

    /**
     * @var \Customers\Entity\UserRelation
     */
    private $relation;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customer.
     *
     * @param \Customers\Entity\Customer|null $customer
     *
     * @return UserRelationJoint
     */
    public function setCustomer(\Customers\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer.
     *
     * @return \Customers\Entity\Customer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set user.
     *
     * @param \Users\Entity\User|null $user
     *
     * @return UserRelationJoint
     */
    public function setUser(\Users\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Users\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set relation.
     *
     * @param \Customers\Entity\UserRelation|null $relation
     *
     * @return UserRelationJoint
     */
    public function setRelation(\Customers\Entity\UserRelation $relation = null)
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * Get relation.
     *
     * @return \Customers\Entity\UserRelation|null
     */
    public function getRelation()
    {
        return $this->relation;
    }
}
