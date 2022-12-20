<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.07.17
 * Time: 11:07
 */

namespace Customers\Service;

use Customers\Entity\Customer;
use Customers\Entity\CustomerData;
use Customers\Form\CustomerForm;
use Customers\Repository\CustomerDataRepository;
use Doctrine\Persistence\ObjectManager;
use Hr\Authentication\AuthenticationService;
use Hr\Entity\DictionaryDetails;
use Users\Entity\User;

class CustomerService
{
    private array $classArray = [
        'Chain', 'Subchain', 'Subchain', 'SaleStage', 'Format', 'Subformat', 'Priority', 'Region', 'Size',
        'Subsize', 'CustomerGroups', 'VisitFrequency', 'LogisticRegion', 'CustomerStatus', 'City', 'Payer',
    ];

    public function __construct(
        private ObjectManager         $objectManager,
        private TemplateService       $templateService,
        private AuthenticationService $authenticationService
    )
    {
    }

    public function add(Customer $customer, $data)
    {
        $customer->getCustomerData()[0]->setCustomer($customer);
        $user = $this->objectManager->getRepository(User::class)->find($this->authenticationService->getIdentity()['id']);

        $customer->setCreator($user);

        if (isset($data['customer']['customerData'][0]['streetPrefix'])) {
            $sp = $data['customer']['customerData'][0]['streetPrefix'];
            /** @var DictionaryDetails $streetPrefix */
            $streetPrefix = $this->objectManager->find(DictionaryDetails::class, $sp);
            $customer->getCustomerData()[0]->setStreetPrefix($streetPrefix ? $streetPrefix->getName() : $sp);
        }

        $this->objectManager->persist($customer);
        $this->objectManager->flush();

        return $customer->getId();
    }

    /**
     * Find customer by id.
     *
     * @param int $id
     * @return Customer|null
     */
    public function find(int $id): ?Customer
    {
        return $this->objectManager->find(Customer::class, $id);
    }

    public function edit(Customer $customer, $data, CustomerData $customerData): void
    {
        $this->objectManager->detach($customerData);
        $activeData = $customer->getActiveData();
        $activeDataClone = clone $activeData;
        $this->objectManager->detach($activeDataClone);
        $this->objectManager->flush();

        $activeData->setName($customerData->getName());
        $activeData->setStreetNumber($customerData->getStreetNumber());
        $activeData->setCity($customerData->getCity());
        $activeData->setLocalNumber($customerData->getLocalNumber());
        $activeData->setNip($customerData->getNip());
        $activeData->setRegon($customerData->getRegon());
        $activeData->setStreetName($customerData->getStreetName());
        $activeData->setStreetPrefix($customerData->getStreetPrefix());
        $activeData->setZipCode($customerData->getZipCode());
        $activeData->setIsActive(false);

        $activeDataClone->setModificationDate(new \DateTime('now'));

        $this->objectManager->persist($activeDataClone);
        $this->objectManager->persist($activeData);
        $this->objectManager->persist($customer);
        $this->objectManager->flush();
    }

    /**
     * @param $data
     * @return array
     */
    public function setDefaultData($data): array
    {
        $default = $this->templateService->fetchAll();
        if (isset($default['customer'])) {
            foreach ($default['customer'] as $key => $value) {
                if (!isset($data['customer'][$key]) || $data['customer'][$key] == '') {
                    if (!empty($default['customer'][$key])) {
                        $data['customer'][$key] = $default['customer'][$key];
                        if ($key == 'customerGroups') {
                            $data['customer'][$key] = [];
                            $data['customer'][$key][] = $value;
                        }
                    }
                }
            }
        }

        if (isset($default['customerData'])) {
            foreach ($default['customerData'] as $key => $value) {
                if (!isset($data['customer']['customerData'][0][$key]) || $data['customer']['customerData'][0][$key] == '') {
                    if (!empty($default['customerData'][$key])) {
                        $data['customer']['customerData'][0][$key] = $value;
                    }
                }
            }
        }

        if (!isset($data['customer']['region']) || empty($data['customer']['region'])) {
            $data['customer']['region'] = $this->authenticationService->getIdentity()['regionDicId'];
        }

        return [$data, $default['customerData']['city'] ?? []];
    }

    /**
     * Adds default data to Customer and customerData after hydration to fields removed from form by Privileges
     *
     * @param Customer     $customer
     * @param CustomerForm $form
     * @param              $data
     */
    public function setDataAfterValidate(Customer $customer, CustomerForm $form, $data): void
    {
        $default = $this->templateService->fetchAll();
        $customerData = $customer->getCustomerData()[0];
        if (isset($default['customer'])) {
            foreach ($default['customer'] as $key => $value) {
                if (!isset($data['customer'][$key])) {
                    if (!empty($default['customer'][$key]) && !$form->getBaseFieldset()->has($key)) {
                        $className = ucfirst($key);
                        $setMethod = 'set' . $className;
                        // shouldn't it be 'customerGroups'?
                        if (method_exists($customer, $setMethod) && $key != 'cutomerGroups') {
                            if (in_array($className, $this->classArray)) {
                                $repository = $this->getNamespace($className);
                                $customer->$setMethod($this->objectManager->getRepository($repository)->find($default['customer'][$key]));
                            } else {
                                $customer->$setMethod($default['customer'][$key]);
                            }
                        }
                    }
                }
            }
        }
        if (empty($default['customer']['region']) && !$customer->getRegion()) {
            $customer->setRegion($this->objectManager->getRepository(DictionaryDetails::class)->find($this->authenticationService->getIdentity()['regionDicId']));
        }
        if (isset($default['customerData'])) {
            foreach ($default['customerData'] as $key => $value) {
                if (!isset($data['customer']['customerData'][0][$key])) {
                    if (!empty($default['customerData'][$key] && !$form->getBaseFieldset()->get('customerData')->has($key))) {
                        $className = ucfirst($key);
                        $setMethod = 'set' . $className;
                        if (method_exists($customerData, $setMethod)) {
                            if (in_array($className, $this->classArray)) {
                                $repository = $this->getNamespace($className);
                                $customerData->$setMethod($this->objectManager->getRepository($repository)->find($default['customerdata'][$key]));
                            } else {
                                $customerData->$setMethod($default['customerData'][$key]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getNamespace($class): string
    {
        switch ($class) {
            case 'Payer':
                return '\Payers\Entity\Payer';
            case 'Subchain' :
                return '\Hr\Entity\Subchain';
            default:
                return '\Hr\Entity\DictionaryDetails';
        }
    }

    /**
     * Prepares customer data obtained from object manager for form hydration.
     *
     * @param array $data
     * @return array
     */
    public function prepareCustomerDataForHydration(array $data): array
    {
        $data['id'] = null;
        $data['customerData'][0]['id'] = null;

        if (isset($data['customerData'][0]['city']['id'])) {
            $data['customerData'][0]['city'] = $data['customerData'][0]['city']['id'];
        }

        foreach ($data as $key => $value) {
            if ($key == 'customerGroups') {
                $tmp = [];
                foreach ($value as $cg) {
                    $tmp[] = $cg['customerGroup']['id'];
                }
                $data[$key] = $tmp;
            } elseif ($key != 'customerData' && is_array($value)) {
                $data[$key] = $value['id'];
            }
        }

        return ['customer' => $data];
    }

    /**
     * Updates customer data field.
     *
     * @param int    $customerId
     * @param string $field
     * @param string $value
     */
    public function updateDataField(int $customerId, string $field, string $value): void
    {
        /** @var CustomerDataRepository $customerDataRepository */
        $customerDataRepository = $this->objectManager->getRepository(CustomerData::class);
        $customerDataRepository->updateField($customerId, $field, $value);
    }

    /**
     * Updates customer field.
     *
     * @param int    $customerId
     * @param string $field
     * @param string $value
     */
    public function updateField(int $customerId, string $field, string $value): void
    {
        /** @var CustomerDataRepository $customerDataRepository */
        $customerDataRepository = $this->objectManager->getRepository(Customer::class);
        $customerDataRepository->updateField($customerId, $field, $value);
    }
}