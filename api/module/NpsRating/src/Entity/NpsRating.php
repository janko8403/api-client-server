<?php

namespace NpsRating\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use NpsRating\Repository\NpsRatingRepository;
use Users\Entity\User;

#[Entity(repositoryClass: NpsRatingRepository::class), Table(name: "npsRatings")]
class NpsRating
{
    #[Id, Column(type: "integer"), GeneratedValue]
    /**
     * @var int
     */
    private $id;

    #[Column(type: "datetime")]
    /**
     * @var \DateTime
     */
    private $date;

    #[Column(type: "string")]
    /**
     * @var string
     */
    private $customerName;

    #[Column(type: "string")]
    /**
     * @var string
     */
    private $email;

    #[Column(type: "integer")]
    /**
     * @var int
     */
    private $rating;

    #[Column(type: "text")]
    /**
     * @var string
     */
    private $comment;

    #[ManyToOne(targetEntity: User::class), JoinColumn(name: "userId", referencedColumnName: "id")]
    /**
     * @var User
     */
    private $user;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return NpsRating
     */
    public function setDate(\DateTime $date): NpsRating
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    /**
     * @param string $customerName
     * @return NpsRating
     */
    public function setCustomerName(string $customerName): NpsRating
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return NpsRating
     */
    public function setRating(int $rating): NpsRating
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return NpsRating
     */
    public function setComment(string $comment): NpsRating
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return NpsRating
     */
    public function setUser(User $user): NpsRating
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return NpsRating
     */
    public function setEmail(string $email): NpsRating
    {
        $this->email = $email;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate()->format(\DateTimeInterface::RFC3339),
            'customerName' => $this->getCustomerName(),
            'rating' => $this->getRating(),
            'comment' => $this->getComment(),
            'email' => $this->getEmail(),
        ];
    }
}