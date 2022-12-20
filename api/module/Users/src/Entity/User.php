<?php

namespace Users\Entity;

use Configuration\Entity\Position;
use Hr\Entity\DictionaryDetails;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Users\Entity\User
     */
    private $supervisor;

    /**
     * @var boolean
     */
    private $isactive = '1';

    /**
     * @var boolean
     */
    private $istester = '0';

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phonenumber;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $region;

    /**
     * @var \Hr\Entity\DictionaryDetails
     */
    private $position;

    /**
     * @var \Configuration\Entity\Position
     */
    private $configurationPosition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groups;

    private bool $tempPassword = false;

    /**
     * @var string|null
     */
    private $company;

    /**
     * @var \DateTime
     */
    private $lastPasswordChange;

    /**
     * @var \DateTime
     */
    private $creationDate;

    /**
     * @var \DateTime
     */
    private $lastLogin;

    /**
     * @var null|DictionaryDetails
     */
    private $chain = null;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $departments;

    /**
     * @var int | null
     */
    private $npsValue;

    /**
     * @var int | null
     */
    private $dailyProductivity;

    /**
     * @var string | null
     */
    private $listOfCategory;

    /**
     * @var string | null
     */
    private $referenceNumber;

    /**
     * @var string|null
     */
    private $passwordToken;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->departments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get isactive
     *
     * @return boolean
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     *
     * @return User
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * Get istester
     *
     * @return boolean
     */
    public function getIstester()
    {
        return $this->istester;
    }

    /**
     * Set istester
     *
     * @param boolean $istester
     *
     * @return User
     */
    public function setIstester($istester)
    {
        $this->istester = $istester;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

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
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get phonenumber
     *
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * Set phonenumber
     *
     * @param string $phonenumber
     *
     * @return User
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;

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
     * @return User
     */
    public function setRegion(\Hr\Entity\DictionaryDetails $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get position
     *
     * @return \Hr\Entity\DictionaryDetails
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set position
     *
     * @param \Hr\Entity\DictionaryDetails $position
     *
     * @return User
     */
    public function setPosition(\Hr\Entity\DictionaryDetails $position = null)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Add group
     *
     * @param \Users\Entity\UserGroup $group
     *
     * @return User
     */
    public function addGroup(\Users\Entity\UserGroup $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \Users\Entity\UserGroup $group
     */
    public function removeGroup(\Users\Entity\UserGroup $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Get supervisor
     *
     * @return \Users\Entity\User
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set supervisor
     *
     * @param \Users\Entity\User $supervisor
     *
     * @return User
     */
    public function setSupervisor(\Users\Entity\User $supervisor = null)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastPasswordChange()
    {
        return $this->lastPasswordChange;
    }

    /**
     * @param \DateTime $lastPasswordChange
     * @return User
     */
    public function setLastPasswordChange(\DateTime $lastPasswordChange): User
    {
        $this->lastPasswordChange = $lastPasswordChange;

        return $this;
    }

    public function getTempPassword(): bool
    {
        return $this->tempPassword;
    }

    public function setTempPassword(bool $tempPassword): User
    {
        $this->tempPassword = $tempPassword;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     * @return User
     */
    public function setCreationDate(\DateTime $creationDate): User
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin(\DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return DictionaryDetails|null
     */
    public function getChain(): ?DictionaryDetails
    {
        return $this->chain;
    }

    /**
     * @param DictionaryDetails|null $chain
     * @return User
     */
    public function setChain(?DictionaryDetails $chain): User
    {
        $this->chain = $chain;

        return $this;
    }

    /**
     * Add department.
     *
     * @param $departments
     * @return User
     */
    public function addDepartments($departments)
    {
        foreach ($departments as $department) {
            $this->departments[] = $department;
        }

        return $this;
    }

    /**
     * Remove department.
     *
     * @param $departments
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDepartments($departments)
    {
        foreach ($departments as $department) {
            return $this->departments->removeElement($department);
        }
    }

    /**
     * Get departments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Gets user's full name.
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->getName() . ' ' . $this->getSurname();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFullNameWithSAP(): string
    {
        return sprintf(
            "%s %s (%s)",
            $this->getName(),
            $this->getSurname(),
            $this->getReferenceNumber()
        );
    }

    /**
     * @return string|null
     */
    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    /**
     * @param string|null $referenceNumber
     * @return User
     */
    public function setReferenceNumber(?string $referenceNumber): User
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNpsValue(): ?int
    {
        return $this->npsValue;
    }

    /**
     * @param int|null $npsValue
     * @return User
     */
    public function setNpsValue(?int $npsValue): User
    {
        $this->npsValue = $npsValue;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDailyProductivity(): ?int
    {
        return $this->dailyProductivity;
    }

    /**
     * @param int|null $dailyProductivity
     * @return User
     */
    public function setDailyProductivity(?int $dailyProductivity): User
    {
        $this->dailyProductivity = $dailyProductivity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getListOfCategory(): ?string
    {
        return $this->listOfCategory;
    }

    /**
     * @param string|null $listOfCategory
     * @return User
     */
    public function setListOfCategory(?string $listOfCategory): User
    {
        $this->listOfCategory = $listOfCategory;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPasswordToken(): ?string
    {
        return $this->passwordToken;
    }

    /**
     * @param string $passwordToken
     * @return User
     */
    public function setPasswordToken(string $passwordToken): User
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    public function isAdminType(int $type): bool
    {
        return $this->getConfigurationPosition()->getId() == $type;
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
     * Get configurationPosition
     *
     * @return \Configuration\Entity\Position
     */
    public function getConfigurationPosition()
    {
        return $this->configurationPosition;
    }

    /**
     * Set configurationPosition
     *
     * @param \Configuration\Entity\Position $configurationPosition
     *
     * @return User
     */
    public function setConfigurationPosition(\Configuration\Entity\Position $configurationPosition = null)
    {
        $this->configurationPosition = $configurationPosition;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    ////////////////////

    /**
     * @param string|null $company
     * @return User
     */
    public function setCompany(?string $company): User
    {
        $this->company = $company;

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->getConfigurationPosition()->getKey() == Position::POSITION_ADMIN;
    }

    public function isRegionalManager(): bool
    {
        return $this->getConfigurationPosition()->getKey() == Position::POSITION_TIKROW_REGIONAL_MANAGER;
    }

    public function isNationalManager(): bool
    {
        return $this->getConfigurationPosition()->getKey() == Position::POSITION_TIKROW_NATIONAL_MANAGER;
    }

}
