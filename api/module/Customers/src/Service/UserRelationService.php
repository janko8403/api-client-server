<?php


namespace Customers\Service;


use Configuration\Entity\Position;
use Customers\Entity\Customer;
use Customers\Entity\UserRelation;
use Customers\Entity\UserRelationJoint;
use Doctrine\Persistence\ObjectManager;
use Hr\Entity\DictionaryDetails;
use Users\Entity\User;

class UserRelationService
{
    private ObjectManager $objectManager;

    /**
     * UserRelationService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Fetches user relations for customer.
     *
     * @param int $customerId
     * @return array
     */
    public function fetchUsersForCustomer(int $customerId): array
    {
        return $this->objectManager->getRepository(UserRelationJoint::class)->fetchUsersForCustomer($customerId);
    }

    /**
     * Gets default user with relation data.
     *
     * @return int[]
     */
    public function getDefaultAddUserData(): array
    {
        return [
            'instance' => 1,
            'marketingCampaign' => 38,
            'position' => 4797,
            'relation' => 5,
        ];
    }

    /**
     * Add a new user and connects him to a customer with relation.
     *
     * @param array    $data
     * @param Customer $customer
     * @throws \Exception
     */
    public function addUserWithRelation(array $data, Customer $customer): void
    {
        $user = new User();
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setEmail($data['email']);
        $user->setPhonenumber($data['phonenumber']);
        $user->setIsactive(true);
        $user->setCreationDate(new \DateTime());
        $user->setPassword('');
        $user->setLogin(bin2hex(random_bytes(10)));

        $user->setPosition($this->objectManager->find(DictionaryDetails::class, $data['position']));
        $user->setConfigurationPosition($this->objectManager->getRepository(Position::class)->findOneBy(['key' => Position::POSITION_CRM_CUSTOMER]));

        $relation = new UserRelationJoint();
        $relation->setCustomer($customer);
        $relation->setUser($user);
        $relation->setRelation($this->objectManager->find(UserRelation::class, $data['relation']));

        $this->objectManager->persist($user);
        $this->objectManager->persist($relation);
        $this->objectManager->flush();
    }
}