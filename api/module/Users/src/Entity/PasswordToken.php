<?php

namespace Users\Entity;

/**
 * PasswordToken
 */
class PasswordToken
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $generationDate;

    /**
     * @var \Users\Entity\User
     */
    private $user;

    /**
     * @var bool
     */
    private $isactive = '1';

    /**
     * @var int
     */
    private $numberOfTries = 0;

    public function __construct()
    {
        $this->generationDate = new \DateTime();
        $this->isactive = 1;
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
     * Set code.
     *
     * @param string $code
     *
     * @return PasswordToken
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set generationDate.
     *
     * @param \DateTime $generationDate
     *
     * @return PasswordToken
     */
    public function setGenerationDate($generationDate)
    {
        $this->generationDate = $generationDate;

        return $this;
    }

    /**
     * Get generationDate.
     *
     * @return \DateTime
     */
    public function getGenerationDate()
    {
        return $this->generationDate;
    }

    /**
     * Set user.
     *
     * @param \Users\Entity\User|null $user
     *
     * @return PasswordToken
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
     * Set isactive.
     *
     * @param bool $isactive
     *
     * @return PasswordToken
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * Get isactive.
     *
     * @return bool
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * Set numberOfTries.
     *
     * @param int $numberOfTries
     *
     * @return PasswordToken
     */
    public function setNumberOfTries($numberOfTries)
    {
        $this->numberOfTries = $numberOfTries;

        return $this;
    }

    /**
     * Get numberOfTries.
     *
     * @return int
     */
    public function getNumberOfTries()
    {
        return $this->numberOfTries;
    }
}
