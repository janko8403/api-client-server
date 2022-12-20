<?php

namespace AssemblyOrders\Entity;

use AssemblyOrders\Repository\AssemblyOrderUserRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: AssemblyOrderUserRepository::class), Table(name: "assemblyOrderUsers")]
class AssemblyOrderUser
{
    const STATUS_HIDDEN = 1;
    const STATUS_ACCEPTED = 2;

    #[Id, Column(type: "integer"), GeneratedValue]
    /** @var int|null */
    private $id;

    #[Column(type: "datetime", nullable: false)]
    /** @var \DateTime */
    private $creationDateTime;

    #[Column(type: "integer", nullable: false)]
    private $status;

    #[ManyToOne(targetEntity: \Users\Entity\User::class), JoinColumn(name: "userId", referencedColumnName: "id")]
    /** @var \Users\Entity\User */
    private $user;

    #[ManyToOne(targetEntity: AssemblyOrder::class, inversedBy: "users"), JoinColumn(name: "`order`", referencedColumnName: "id")]
    /** @var AssemblyOrder */
    private $order;

    public function __construct()
    {
        $this->creationDateTime = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreationDateTime(): \DateTime
    {
        return $this->creationDateTime;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return AssemblyOrderUser
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \Users\Entity\User
     */
    public function getUser(): \Users\Entity\User
    {
        return $this->user;
    }

    /**
     * @param \Users\Entity\User $user
     * @return AssemblyOrderUser
     */
    public function setUser(\Users\Entity\User $user): AssemblyOrderUser
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return AssemblyOrder
     */
    public function getOrder(): AssemblyOrder
    {
        return $this->order;
    }

    /**
     * @param AssemblyOrder $order
     * @return AssemblyOrderUser
     */
    public function setOrder(AssemblyOrder $order): AssemblyOrderUser
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDisplayDate(): ?\DateTime
    {
        return $this->displayDate;
    }

    /**
     * @param \DateTime|null $displayDate
     * @return AssemblyOrderUser
     */
    public function setDisplayDate(?\DateTime $displayDate): AssemblyOrderUser
    {
        $this->displayDate = $displayDate;

        return $this;
    }
}