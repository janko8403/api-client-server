<?php

namespace Users\Entity;

/**
 * UserGroup
 */
class UserGroup
{
    /**
     * @var integer
     */
    private $id;


    /**
     * @var integer
     */
    private $groupId;

    /**
     * @var \Users\Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get groupId
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return UserGroup
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Users\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param \Users\Entity\User $user
     *
     * @return UserGroup
     */
    public function setUser(\Users\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}
