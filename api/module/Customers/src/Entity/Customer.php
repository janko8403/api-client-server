<?php

namespace Customers\Entity;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Hr\Entity\Location;

/**
 * Customer
 */
class Customer
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isActive = '1';

    /**
     * @var integer
     */
    private $innerCustomerId;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var \DateTime
     */
    private $maxContactDate;

    /**
     * @var string
     */
    private $additionalInformation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $customerData;

    /**
     * @var \Hr\Entity\Subchain
     */
    private $subchain;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $saleStage;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $format;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $priority;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $region;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $size;

    /**
     * @var \Users\Entity\User
     */
    private $creator;

    /**
     * @var \Hr\Entity\Dictionarydetails
     */
    private $customerStatus;

    /**
     * @var \Hr\Entity\Dictionarydetails
     */
    private $logisticRegion;

    /**
     * @var \Hr\Entity\Dictionarydetails
     */
    private $visitsFrequency;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $chain;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $subformat;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $subsize;

    /**
     * @var \DateTime
     */
    private $saleStageDateChange;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var Location
     */
    private $location;

    public function __construct()
    {
        $this->customerData = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = new \DateTime();
    }

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
     * @return float
     */
    public function getBaseTotalReduced()
    {
        return $this->baseTotalReduced;
    }

    /**
     * @param float $baseTotalReduced
     * @return Customer
     */
    public function setBaseTotalReduced(float $baseTotalReduced): Customer
    {
        $this->baseTotalReduced = $baseTotalReduced;

        return $this;
    }


    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Customer
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get reported
     *
     * @return boolean
     */
    public function getReported()
    {
        return $this->reported;
    }

    /**
     * Set reported
     *
     * @param boolean $reported
     *
     * @return Customer
     */
    public function setReported($reported)
    {
        $this->reported = $reported;

        return $this;
    }

    /**
     * Get numberOfVisits
     *
     * @return int
     */
    public function getNumberOfVisits()
    {
        return $this->numberOfVisits;
    }

    /**
     * Set numberOfVisits
     *
     * @param int $numberOfVisits
     *
     * @return Customer
     */
    public function setNumberOfVisits($numberOfVisits)
    {
        $this->numberOfVisits = $numberOfVisits;

        return $this;
    }

    /**
     * Get isEmailConfirmationRequired
     *
     * @return boolean
     */
    public function getIsEmailConfirmationRequired()
    {
        return $this->isEmailConfirmationRequired;
    }

    /**
     * Set isEmailConfirmationRequired
     *
     * @param boolean $isEmailConfirmationRequired
     *
     * @return Customer
     */
    public function setIsEmailConfirmationRequired($isEmailConfirmationRequired)
    {
        $this->isEmailConfirmationRequired = $isEmailConfirmationRequired;

        return $this;
    }

    /**
     * Get isTransactive
     *
     * @return boolean
     */
    public function getIsTransactive()
    {
        return $this->isTransactive;
    }

    /**
     * Set isTransactive
     *
     * @param boolean $isTransactive
     *
     * @return Customer
     */
    public function setIsTransactive($isTransactive)
    {
        $this->isTransactive = $isTransactive;

        return $this;
    }

    /**
     * Get innerCustomerId
     *
     * @return integer
     */
    public function getInnerCustomerId()
    {
        return $this->innerCustomerId;
    }

    /**
     * Set innerCustomerId
     *
     * @param integer $innerCustomerId
     *
     * @return Customer
     */
    public function setInnerCustomerId($innerCustomerId)
    {
        $this->innerCustomerId = $innerCustomerId;

        return $this;
    }

    /**
     * Get creditLimit
     *
     * @return string
     */
    public function getCreditLimit()
    {
        return $this->creditLimit;
    }

    /**
     * Set creditLimit
     *
     * @param string $creditLimit
     *
     * @return Customer
     */
    public function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;

        return $this;
    }

    /**
     * Get acreage
     *
     * @return integer
     */
    public function getAcreage()
    {
        return $this->acreage;
    }

    /**
     * Set acreage
     *
     * @param integer $acreage
     *
     * @return Customer
     */
    public function setAcreage($acreage)
    {
        $this->acreage = $acreage;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Customer
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Customer
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get maxContactDate
     *
     * @return \DateTime
     */
    public function getMaxContactDate()
    {
        return $this->maxContactDate;
    }

    /**
     * Set maxContactDate
     *
     * @param \DateTime $maxContactDate
     *
     * @return Customer
     */
    public function setMaxContactDate($maxContactDate)
    {
        $this->maxContactDate = $maxContactDate;

        return $this;
    }

    /**
     * Get additionalInformation
     *
     * @return string
     */
    public function getAdditionalInformation()
    {
        return $this->additionalInformation;
    }

    /**
     * Set additionalInformation
     *
     * @param string $additionalInformation
     *
     * @return Customer
     */
    public function setAdditionalInformation($additionalInformation)
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    /**
     * Add customerDatum
     *
     * @param \Customers\Entity\CustomerData $customerDatum
     *
     * @return Customer
     */
    public function addCustomerData($customerDatum)
    {
        foreach ($customerDatum as $cd) {
            $this->customerData[] = $cd;
        }

        return $this;
    }

    /**
     * Remove customerDatum
     *
     * @param \Customers\Entity\CustomerData $customerDatum
     */
    public function removeCustomerData($customerDatum)
    {
        foreach ($customerDatum as $cd) {
            $this->customerData->removeElement($cd);
        }
    }

    /**
     * Get subchain
     *
     * @return \Hr\Entity\Subchain
     */
    public function getSubchain()
    {
        return $this->subchain;
    }

    /**
     * Set subchain
     *
     * @param \Hr\Entity\Subchain $subchain
     *
     * @return Customer
     */
    public function setSubchain(\Hr\Entity\Subchain $subchain = null)
    {
        $this->subchain = $subchain;

        return $this;
    }

    /**
     * Get saleStage
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getSaleStage()
    {
        return $this->saleStage;
    }

    /**
     * Set saleStage
     *
     * @param \Hr\Entity\DictionaryDetails $saleStage
     *
     * @return Customer
     */
    public function setSaleStage(\Hr\Entity\DictionaryDetails $saleStage = null)
    {
        $this->saleStage = $saleStage;

        return $this;
    }

    /**
     * Get format
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format
     *
     * @param \Hr\Entity\DictionaryDetails $format
     *
     * @return Customer
     */
    public function setFormat(\Hr\Entity\DictionaryDetails $format = null)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set priority
     *
     * @param \Hr\Entity\DictionaryDetails $priority
     *
     * @return Customer
     */
    public function setPriority(\Hr\Entity\DictionaryDetails $priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get region
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set region
     *
     * @param \Hr\Entity\DictionaryDetails $region
     *
     * @return Customer
     */
    public function setRegion(\Hr\Entity\DictionaryDetails $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get size
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set size
     *
     * @param \Hr\Entity\DictionaryDetails $size
     *
     * @return Customer
     */
    public function setSize(\Hr\Entity\DictionaryDetails $size = null)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Users\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set creator
     *
     * @param \Users\Entity\User $creator
     *
     * @return Customer
     */
    public function setCreator(\Users\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get customerStatus
     *
     * @return \Hr\Entity\Dictionarydetails
     */
    public function getCustomerStatus()
    {
        return $this->customerStatus;
    }

    /**
     * Set customerStatus
     *
     * @param \Hr\Entity\Dictionarydetails $customerStatus
     *
     * @return Customer
     */
    public function setCustomerStatus(\Hr\Entity\DictionaryDetails $customerStatus = null)
    {
        $this->customerStatus = $customerStatus;

        return $this;
    }

    /**
     * Get logisticRegion
     *
     * @return \Hr\Entity\Dictionarydetails
     */
    public function getLogisticRegion()
    {
        return $this->logisticRegion;
    }

    /**
     * Set logisticRegion
     *
     * @param \Hr\Entity\Dictionarydetails $logisticRegion
     *
     * @return Customer
     */
    public function setLogisticRegion(\Hr\Entity\DictionaryDetails $logisticRegion = null)
    {
        $this->logisticRegion = $logisticRegion;

        return $this;
    }

    /**
     * Get visitsFrequency
     *
     * @return \Hr\Entity\Dictionarydetails
     */
    public function getVisitsFrequency()
    {
        return $this->visitsFrequency;
    }

    /**
     * Set visitsFrequency
     *
     * @param \Hr\Entity\Dictionarydetails $visitsFrequency
     *
     * @return Customer
     */
    public function setVisitsFrequency(\Hr\Entity\DictionaryDetails $visitsFrequency = null)
    {
        $this->visitsFrequency = $visitsFrequency;

        return $this;
    }

    /**
     * Get rotation
     *
     * @return integer
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * Set rotation
     *
     * @param integer $rotation
     *
     * @return Customer
     */
    public function setRotation($rotation)
    {
        $this->rotation = $rotation;

        return $this;
    }

    /**
     * Get netTotalReduced
     *
     * @return float
     */
    public function getNetTotalReduced()
    {
        return $this->netTotalReduced;
    }

    /**
     * Set netTotalReduced
     *
     * @param float $netTotalReduced
     *
     * @return Customer
     */
    public function setNetTotalReduced($netTotalReduced)
    {
        $this->netTotalReduced = $netTotalReduced;

        return $this;
    }

    /**
     * Get timesMarginTotal
     *
     * @return float
     */
    public function getTimesMarginTotal()
    {
        return $this->timesMarginTotal;
    }

    /**
     * Set timesMarginTotal
     *
     * @param float $timesMarginTotal
     *
     * @return Customer
     */
    public function setTimesMarginTotal($timesMarginTotal)
    {
        $this->timesMarginTotal = $timesMarginTotal;

        return $this;
    }

    /**
     * Get profitability
     *
     * @return float
     */
    public function getProfitability()
    {
        return $this->profitability;
    }

    /**
     * Set profitability
     *
     * @param float $profitability
     *
     * @return Customer
     */
    public function setProfitability($profitability)
    {
        $this->profitability = $profitability;

        return $this;
    }

    /**
     * Get profitabilityThreshold
     *
     * @return float
     */
    public function getProfitabilityThreshold()
    {
        return $this->profitabilityThreshold;
    }

    /**
     * Set profitabilityThreshold
     *
     * @param float $profitabilityThreshold
     *
     * @return Customer
     */
    public function setProfitabilityThreshold($profitabilityThreshold)
    {
        $this->profitabilityThreshold = $profitabilityThreshold;

        return $this;
    }

    /**
     * Get chain
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getChain()
    {
        return $this->chain;
    }

    /**
     * Set chain
     *
     * @param \Hr\Entity\DictionaryDetails $chain
     *
     * @return Customer
     */
    public function setChain(\Hr\Entity\DictionaryDetails $chain = null)
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Get subformat
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getSubformat()
    {
        return $this->subformat;
    }

    /**
     * Set subformat
     *
     * @param \Hr\Entity\DictionaryDetails $subformat
     *
     * @return Customer
     */
    public function setSubformat(\Hr\Entity\DictionaryDetails $subformat = null)
    {
        $this->subformat = $subformat;

        return $this;
    }

    /**
     * Get subsize
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getSubsize()
    {
        return $this->subsize;
    }

    /**
     * Set subsize
     *
     * @param \Hr\Entity\DictionaryDetails $subsize
     *
     * @return Customer
     */
    public function setSubsize(\Hr\Entity\DictionaryDetails $subsize = null)
    {
        $this->subsize = $subsize;

        return $this;
    }

    /**
     * Get www
     *
     * @return string
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Set www
     *
     * @param string $www
     *
     * @return Customer
     */
    public function setWww($www)
    {
        $this->www = $www;

        return $this;
    }

    /**
     * Add customerDatum
     *
     * @param \Customers\Entity\CustomerData $customerDatum
     *
     * @return Customer
     */
    public function addCustomerDatum(\Customers\Entity\CustomerData $customerDatum)
    {
        $this->customerData[] = $customerDatum;

        return $this;
    }

    /**
     * Remove customerDatum
     *
     * @param \Customers\Entity\CustomerData $customerDatum
     */
    public function removeCustomerDatum(\Customers\Entity\CustomerData $customerDatum)
    {
        $this->customerData->removeElement($customerDatum);
    }

    /**
     * Get saleStageDateChange
     *
     * @return \DateTime
     */
    public function getSaleStageDateChange()
    {
        return $this->saleStageDateChange;
    }

    /**
     * Set saleStageDateChange
     *
     * @param \DateTime $saleStageDateChange
     *
     * @return Customer
     */
    public function setSaleStageDateChange($saleStageDateChange)
    {
        $this->saleStageDateChange = $saleStageDateChange;

        return $this;
    }

    /**
     * Get isPremium
     *
     * @return boolean
     */
    public function getIsPremium()
    {
        return $this->isPremium;
    }

    /**
     * Set isPremium
     *
     * @param boolean $isPremium
     *
     * @return Customer
     */
    public function setIsPremium($isPremium)
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return Customer
     */
    public function setCategory(?Category $category): Customer
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Customer
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Customer
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFte(): ?int
    {
        return $this->fte;
    }

    /**
     * @param int|null $fte
     * @return Customer
     */
    public function setFte(?int $fte): Customer
    {
        $this->fte = $fte;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getSplitDegree(): ?float
    {
        return $this->splitDegree;
    }

    /**
     * @param float|null $splitDegree
     * @return Customer
     */
    public function setSplitDegree(?float $splitDegree): Customer
    {
        $this->splitDegree = $splitDegree;

        return $this;
    }

    /**
     * @return bool
     */
    public function getBlockPayments(): bool
    {
        return $this->blockPayments;
    }

    /**
     * @param bool $blockPayments
     * @return Customer
     */
    public function setBlockPayments(bool $blockPayments): Customer
    {
        $this->blockPayments = $blockPayments;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     * @return Customer
     */
    public function setLocation(?Location $location): Customer
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     * @return Customer
     */
    public function setIsNew(bool $isNew): Customer
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHotLead(): bool
    {
        return $this->hotLead;
    }

    /**
     * @param bool $hotLead
     * @return Customer
     */
    public function setHotLead(bool $hotLead): Customer
    {
        $this->hotLead = $hotLead;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getKeyAccountType(): ?int
    {
        return $this->keyAccountType;
    }

    /**
     * @param int|null $keyAccountType
     * @return Customer
     */
    public function setKeyAccountType(?int $keyAccountType): Customer
    {
        $this->keyAccountType = $keyAccountType;

        return $this;
    }

    /**
     * Checks wheather customer has GPS coordinates.
     *
     * @return bool
     */
    public function hasCoordinates(): bool
    {
        return !empty($this->getLongitude()) && !empty($this->getLatitude());
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /////////////////////

    public function updateSaleStageChangeDate(PreUpdateEventArgs $args)
    {
        if ($args->hasChangedField('saleStage')) {
            $args->getObject()->setSaleStageDateChange(new \DateTime());
        }
    }

    public function toArray(): array
    {
        $data = $this->getActiveData();

        return [
            'name' => $data->getName(),
            'address' => $data->getAddress(),
            'zipCode' => $data->getZipCode(),
            'city' => $data->getCity()?->getName(),
        ];
    }

    /**
     * Gets an active customer data.
     *
     * @return CustomerData
     */
    public function getActiveData()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('isActive', true))
            ->setFirstResult(0)
            ->setMaxResults(1);

        return $this->getCustomerData()->matching($criteria)->first();
    }

    /**
     * Get customerData
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }

    public function getName(): string
    {
        return $this->getActiveData()->getName();
    }
}