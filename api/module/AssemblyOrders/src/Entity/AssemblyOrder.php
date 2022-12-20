<?php

namespace AssemblyOrders\Entity;

use AssemblyOrders\Repository\AssemblyOrderRepository;
use Customers\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Users\Entity\User;

#[Entity(repositoryClass: AssemblyOrderRepository::class), Table(name: "assemblyOrders")]
class AssemblyOrder
{
    #[Id, Column(type: "integer"), GeneratedValue]
    /** @var int|null */
    private $id;

    #[Column(type: "integer", nullable: false)]
    /** @var int|null */
    private $idMeasurementOrder;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $idInstallationOrder;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $idParentInstallationOrder;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $measurementStatus;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $installationStatus;

    #[Column(type: "string", nullable: false)]
    /** @var string|null */
    private $idStore;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $idUser;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $assemblyCreatorName;

    #[Column(type: "datetime", nullable: false)]
    /** @var \DateTime */
    private $creationDateTime;

    #[Column(type: "datetime", nullable: true)]
    /** @var \DateTime|null */
    private $updateDateTime;

    #[Column(type: "datetime", nullable: true)]
    /** @var \DateTime|null */
    private $deliveryDateTime;

    #[Column(type: "datetime", nullable: true)]
    /** @var \DateTime|null */
    private $expectedContactDateTime;

    #[Column(type: "datetime", nullable: true)]
    /** @var \DateTime|null */
    private $expectedInstallationDateTime;

    #[Column(type: "datetime", nullable: true)]
    /** @var \DateTime|null */
    private $acceptedInstallationDateTime;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $floorCarpetMeters;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $floorPanelMeters;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $floorWoodMeters;

    #[Column(type: "integer", nullable: true)]
    /** @var int|null */
    private $doorNumber;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $notificationEmail;

    #[Column(type: "float", nullable: false)]
    /** @var float|null */
    private $estimatedCostNet;

    #[Column(type: "string", nullable: false)]
    /** @var string|null */
    private $installationCity;

    #[Column(type: "string", nullable: false)]
    /** @var string|null */
    private $installationAddress;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $installationZipCode;

    #[Column(type: "string", nullable: false)]
    /** @var string|null */
    private $installationName;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $installationPhoneNumber;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $installationEmail;

    #[Column(type: "string", nullable: true)]
    /** @var string|null */
    private $installationNote;

    #[OneToMany(mappedBy: "order", targetEntity: Ranking::class)]
    private $rankings;

    #[OneToMany(mappedBy: "order", targetEntity: AssemblyOrderUser::class)]
    private $users;

    #[Column(type: "boolean")]
    /** @var bool */
    private $taken = false;

    #[ManyToOne(targetEntity: Customer::class), JoinColumn(name: "customerId", referencedColumnName: "id")]
    /** @var Customer */
    private $customer;

    public function __construct()
    {
        $this->creationDateTime = new \DateTime();
        $this->rankings = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getIdMeasurementOrder(): ?int
    {
        return $this->idMeasurementOrder;
    }

    /**
     * @param int $idMeasurementOrder
     * @return AssemblyOrder
     */
    public function setIdMeasurementOrder(int $idMeasurementOrder): AssemblyOrder
    {
        $this->idMeasurementOrder = $idMeasurementOrder;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdInstallationOrder(): ?int
    {
        return $this->idInstallationOrder;
    }

    /**
     * @param int|null $idInstallationOrder
     * @return AssemblyOrder
     */
    public function setIdInstallationOrder(?int $idInstallationOrder): AssemblyOrder
    {
        $this->idInstallationOrder = $idInstallationOrder;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdParentInstallationOrder(): ?int
    {
        return $this->idParentInstallationOrder;
    }

    /**
     * @param int|null $idParentInstallationOrder
     * @return AssemblyOrder
     */
    public function setIdParentInstallationOrder(?int $idParentInstallationOrder): AssemblyOrder
    {
        $this->idParentInstallationOrder = $idParentInstallationOrder;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMeasurementStatus(): ?string
    {
        return $this->measurementStatus;
    }

    /**
     * @param string|null $measurementStatus
     * @return AssemblyOrder
     */
    public function setMeasurementStatus(?string $measurementStatus): AssemblyOrder
    {
        $this->measurementStatus = $measurementStatus;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationStatus(): ?string
    {
        return $this->installationStatus;
    }

    /**
     * @param string|null $installationStatus
     * @return AssemblyOrder
     */
    public function setInstallationStatus(?string $installationStatus): AssemblyOrder
    {
        $this->installationStatus = $installationStatus;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdStore(): ?string
    {
        return $this->idStore;
    }

    /**
     * @param string $idStore
     * @return AssemblyOrder
     */
    public function setIdStore(string $idStore): AssemblyOrder
    {
        $this->idStore = $idStore;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    /**
     * @param int|null $idUser
     * @return AssemblyOrder
     */
    public function setIdUser(?int $idUser): AssemblyOrder
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAssemblyCreatorName(): ?string
    {
        return $this->assemblyCreatorName;
    }

    /**
     * @param string|null $assemblyCreatorName
     * @return AssemblyOrder
     */
    public function setAssemblyCreatorName(?string $assemblyCreatorName): AssemblyOrder
    {
        $this->assemblyCreatorName = $assemblyCreatorName;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDateTime(): \DateTime
    {
        return $this->creationDateTime;
    }

    /**
     * @param \DateTime $creationDateTime
     * @return AssemblyOrder
     */
    public function setCreationDateTime(\DateTime $creationDateTime): AssemblyOrder
    {
        $this->creationDateTime = $creationDateTime;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdateDateTime(): ?\DateTime
    {
        return $this->updateDateTime;
    }

    /**
     * @param \DateTime|null $updateDateTime
     * @return AssemblyOrder
     */
    public function setUpdateDateTime(?\DateTime $updateDateTime): AssemblyOrder
    {
        $this->updateDateTime = $updateDateTime;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeliveryDateTime(): ?\DateTime
    {
        return $this->deliveryDateTime;
    }

    /**
     * @param \DateTime|null $deliveryDateTime
     * @return AssemblyOrder
     */
    public function setDeliveryDateTime(?\DateTime $deliveryDateTime): AssemblyOrder
    {
        $this->deliveryDateTime = $deliveryDateTime;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpectedContactDateTime(): ?\DateTime
    {
        return $this->expectedContactDateTime;
    }

    /**
     * @param \DateTime|null $expectedContactDateTime
     * @return AssemblyOrder
     */
    public function setExpectedContactDateTime(?\DateTime $expectedContactDateTime): AssemblyOrder
    {
        $this->expectedContactDateTime = $expectedContactDateTime;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpectedInstallationDateTime(): ?\DateTime
    {
        return $this->expectedInstallationDateTime;
    }

    /**
     * @param \DateTime|null $expectedInstallationDateTime
     * @return AssemblyOrder
     */
    public function setExpectedInstallationDateTime(?\DateTime $expectedInstallationDateTime): AssemblyOrder
    {
        $this->expectedInstallationDateTime = $expectedInstallationDateTime;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getAcceptedInstallationDateTime(): ?\DateTime
    {
        return $this->acceptedInstallationDateTime;
    }

    /**
     * @param \DateTime|null $acceptedInstallationDateTime
     * @return AssemblyOrder
     */
    public function setAcceptedInstallationDateTime(?\DateTime $acceptedInstallationDateTime): AssemblyOrder
    {
        $this->acceptedInstallationDateTime = $acceptedInstallationDateTime;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFloorCarpetMeters(): ?int
    {
        return $this->floorCarpetMeters;
    }

    /**
     * @param int|null $floorCarpetMeters
     * @return AssemblyOrder
     */
    public function setFloorCarpetMeters(?int $floorCarpetMeters): AssemblyOrder
    {
        $this->floorCarpetMeters = $floorCarpetMeters;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFloorPanelMeters(): ?int
    {
        return $this->floorPanelMeters;
    }

    /**
     * @param int|null $floorPanelMeters
     * @return AssemblyOrder
     */
    public function setFloorPanelMeters(?int $floorPanelMeters): AssemblyOrder
    {
        $this->floorPanelMeters = $floorPanelMeters;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFloorWoodMeters(): ?int
    {
        return $this->floorWoodMeters;
    }

    /**
     * @param int|null $floorWoodMeters
     * @return AssemblyOrder
     */
    public function setFloorWoodMeters(?int $floorWoodMeters): AssemblyOrder
    {
        $this->floorWoodMeters = $floorWoodMeters;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDoorNumber(): ?int
    {
        return $this->doorNumber;
    }

    /**
     * @param int|null $doorNumber
     * @return AssemblyOrder
     */
    public function setDoorNumber(?int $doorNumber): AssemblyOrder
    {
        $this->doorNumber = $doorNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotificationEmail(): ?string
    {
        return $this->notificationEmail;
    }

    /**
     * @param string|null $notificationEmail
     * @return AssemblyOrder
     */
    public function setNotificationEmail(?string $notificationEmail): AssemblyOrder
    {
        $this->notificationEmail = $notificationEmail;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getEstimatedCostNet(): ?float
    {
        return $this->estimatedCostNet;
    }

    /**
     * @param float|null $estimatedCostNet
     * @return AssemblyOrder
     */
    public function setEstimatedCostNet(?float $estimatedCostNet): AssemblyOrder
    {
        $this->estimatedCostNet = $estimatedCostNet;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationCity(): ?string
    {
        return $this->installationCity;
    }

    /**
     * @param string $installationCity
     * @return AssemblyOrder
     */
    public function setInstallationCity(string $installationCity): AssemblyOrder
    {
        $this->installationCity = $installationCity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationAddress(): ?string
    {
        return $this->installationAddress;
    }

    /**
     * @param string $installationAddress
     * @return AssemblyOrder
     */
    public function setInstallationAddress(string $installationAddress): AssemblyOrder
    {
        $this->installationAddress = $installationAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationZipCode(): ?string
    {
        return $this->installationZipCode;
    }

    /**
     * @param string|null $installationZipCode
     * @return AssemblyOrder
     */
    public function setInstallationZipCode(?string $installationZipCode): AssemblyOrder
    {
        $this->installationZipCode = $installationZipCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationName(): ?string
    {
        return $this->installationName;
    }

    /**
     * @param string $installationName
     * @return AssemblyOrder
     */
    public function setInstallationName(string $installationName): AssemblyOrder
    {
        $this->installationName = $installationName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationPhoneNumber(): ?string
    {
        return $this->installationPhoneNumber;
    }

    /**
     * @param string|null $installationPhoneNumber
     * @return AssemblyOrder
     */
    public function setInstallationPhoneNumber(?string $installationPhoneNumber): AssemblyOrder
    {
        $this->installationPhoneNumber = $installationPhoneNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationEmail(): ?string
    {
        return $this->installationEmail;
    }

    /**
     * @param string|null $installationEmail
     * @return AssemblyOrder
     */
    public function setInstallationEmail(?string $installationEmail): AssemblyOrder
    {
        $this->installationEmail = $installationEmail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstallationNote(): ?string
    {
        return $this->installationNote;
    }

    /**
     * @param string|null $installationNote
     * @return AssemblyOrder
     */
    public function setInstallationNote(?string $installationNote): AssemblyOrder
    {
        $this->installationNote = $installationNote;

        return $this;
    }

    public function getRankings(): Collection
    {
        return $this->rankings;
    }

    /**
     * @return bool
     */
    public function isTaken(): bool
    {
        return $this->taken;
    }

    /**
     * @param bool $taken
     * @return AssemblyOrder
     */
    public function setTaken(bool $taken): AssemblyOrder
    {
        $this->taken = $taken;

        return $this;
    }

    public function toArray(?User $user = null): array
    {
        $data = [
            'id' => $this->id,
            'idMeasurementOrder' => $this->idMeasurementOrder,
            'idInstallationOrder' => $this->idInstallationOrder,
            'idParentInstallationOrder' => $this->idParentInstallationOrder,
            'measurementStatus' => $this->measurementStatus,
            'installationStatus' => $this->installationStatus,
            'idStore' => $this->idStore,
            'idUser' => $this->idUser,
            'assemblyCreatorName' => $this->assemblyCreatorName,
            'creationDateTime' => $this->creationDateTime?->format(\DateTimeInterface::RFC3339),
            'updateDateTime' => $this->updateDateTime?->format(\DateTimeInterface::RFC3339),
            'deliveryDateTime' => $this->deliveryDateTime?->format(\DateTimeInterface::RFC3339),
            'expectedContactDateTime' => $this->expectedContactDateTime?->format(\DateTimeInterface::RFC3339),
            'expectedInstallationDateTime' => $this->expectedInstallationDateTime?->format(\DateTimeInterface::RFC3339),
            'acceptedInstallationDateTime' => $this->acceptedInstallationDateTime?->format(\DateTimeInterface::RFC3339),
            'floorCarpetMeters' => $this->floorCarpetMeters,
            'floorPanelMeters' => $this->floorPanelMeters,
            'floorWoodMeters' => $this->floorWoodMeters,
            'doorNumber' => $this->doorNumber,
            'notificationEmail' => $this->notificationEmail,
            'estimatedCostNet' => $this->estimatedCostNet,
            'installationCity' => $this->installationCity,
            'installationAddress' => $this->installationAddress,
            'installationZipCode' => $this->installationZipCode,
            'installationName' => $this->installationName,
            'installationPhoneNumber' => $this->installationPhoneNumber,
            'installationEmail' => $this->installationEmail,
            'installationNote' => $this->installationNote,
            'customer' => $this->getCustomer()->toArray(),
        ];

        if ($user) {
            $showDetails = $this->getUsers()->exists(
                fn($key, $elem) => $elem->getUser()->getId() == $user->getId() && $elem->getStatus() == AssemblyOrderUser::STATUS_ACCEPTED
            );
            if (!$showDetails) {
                $data['installationName'] = null;
                $data['installationPhoneNumber'] = null;
                $data['installationEmail'] = null;
                $data['installationNote'] = null;
            }
        }

        return $data;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return AssemblyOrder
     */
    public function setCustomer(Customer $customer): AssemblyOrder
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccepted(): ?AssemblyOrderUser
    {
        $orderUser = $this->getUsers()->filter(fn($u) => $u->getStatus() == AssemblyOrderUser::STATUS_ACCEPTED)->first();

        return $orderUser ?: null;
    }
}