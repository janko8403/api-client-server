<?php

namespace AssemblyOrders\Entity;

use AssemblyOrders\Repository\RankingRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Users\Entity\User;

#[Entity(repositoryClass: RankingRepository::class), Table(name: "assemblyOrderRankings")]
class Ranking
{
    #[Id, Column(type: "integer"), GeneratedValue]
    /** @var int */
    private $id;

    #[Column(type: "integer")]
    /** @var int */
    private $position;

    #[Column(type: "datetime", nullable: true)]
    /** @var \DateTime | null */
    private $displayDate;

    #[ManyToOne(targetEntity: AssemblyOrder::class, inversedBy: "rankings"), JoinColumn(name: "orderId", referencedColumnName: "id")]
    /** @var AssemblyOrder */
    private $order;

    #[ManyToOne(targetEntity: User::class), JoinColumn(name: "userId", referencedColumnName: "id")]
    /** @var AssemblyOrderUser */
    private $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Ranking
     */
    public function setId(int $id): Ranking
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Ranking
     */
    public function setPosition(int $position): Ranking
    {
        $this->position = $position;

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
     * @return Ranking
     */
    public function setOrder(AssemblyOrder $order): Ranking
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return AssemblyOrderUser
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param AssemblyOrderUser $user
     * @return Ranking
     */
    public function setUser(User $user): Ranking
    {
        $this->user = $user;

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
     * @return Ranking
     */
    public function setDisplayDate(?\DateTime $displayDate): Ranking
    {
        $this->displayDate = $displayDate;

        return $this;
    }
}