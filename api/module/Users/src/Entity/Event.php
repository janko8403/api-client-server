<?php

namespace Users\Entity;

/**
 * Event
 */
class Event
{
    const TYPE_PHONE = 1;
    const TYPE_NOTIFICATION = 2;
    const TYPE_REVOKE = 3;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var \Users\Entity\User
     */
    private $user;

    /**
     * @var \Users\Entity\User
     */
    private $creatingUser;

    /**
     * @var \Commissions\Entity\Commission
     */
    private $commission;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }

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
     * Set type.
     *
     * @param int $type
     *
     * @return Event
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime $creationDate
     *
     * @return Event
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Event
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user.
     *
     * @param \Users\Entity\User|null $user
     *
     * @return Event
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
     * Set creatingUser.
     *
     * @param \Users\Entity\User|null $creatingUser
     *
     * @return Event
     */
    public function setCreatingUser(\Users\Entity\User $creatingUser = null)
    {
        $this->creatingUser = $creatingUser;

        return $this;
    }

    /**
     * Get creatingUser.
     *
     * @return \Users\Entity\User|null
     */
    public function getCreatingUser()
    {
        return $this->creatingUser;
    }

    /**
     * Set commission.
     *
     * @param \Commissions\Entity\Commission|null $commission
     *
     * @return Event
     */
    public function setCommission(\Commissions\Entity\Commission $commission = null)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission.
     *
     * @return \Commissions\Entity\Commission|null
     */
    public function getCommission()
    {
        return $this->commission;
    }
}
