<?php

namespace Notifications\Entity;

/**
 * Log
 */
class Log
{
    const TYPE_NEW_EXPRESS = 1;

    const TRANSPORT_FCM = 1;
    const TRANSPORT_SMS = 2;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $transport;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \Users\Entity\User
     */
    private $user;

    /**
     * Log constructor.
     */
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
     * @return Log
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
     * @return Log
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
     * Set user.
     *
     * @param \Users\Entity\User|null $user
     *
     * @return Log
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
     * @return int
     */
    public function getTransport(): int
    {
        return $this->transport;
    }

    /**
     * @param int $transport
     * @return Log
     */
    public function setTransport(int $transport): Log
    {
        $this->transport = $transport;
        return $this;
    }
}
