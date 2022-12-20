<?php

/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.07.2017
 * Time: 12:20
 */

namespace Settings\Service;

use Customers\Entity\Customer;
use Customers\Entity\CustomerData;
use Doctrine\Persistence\ObjectManager;
use Settings\Entity\AddressBar;
use Hr\Entity\RegionSubregionJoint;
use Users\Entity\User;

class AddressBarService
{
    private ObjectManager $objectManager;

    /**
     * AddressBarService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Saves address bar data.
     *
     * @param array $data
     */
    public function save(array $data)
    {
        foreach ($data as $name => $value) {
            if ($name == 'files' || $name == 'save') {
                continue;
            }

            $addressBar = $this->objectManager->find(AddressBar::class, $name);

            if (!$addressBar) {
                $addressBar = new AddressBar();
                $addressBar->setName($name);
            }

            $addressBar->setValue($value);
            $this->objectManager->persist($addressBar);
        }

        $this->objectManager->flush();
    }

    /**
     * Gets address bar data.
     *
     * @return array
     */
    public function getAll(): array
    {
        $addressBar = [];
        $data = $this->objectManager->getRepository(AddressBar::class)->findAll();

        foreach ($data as $d) {
            $addressBar[$d->getName()] = $d->getValue();
        }

        return $addressBar;
    }

    /**
     * Gets html for customer address bar.
     *
     * @param int      $customerId
     * @param int|null $userRegionId
     * @return string
     */
    public function getBarCustomer(int $customerId, int $userRegionId = null): string
    {
        $userRegionId = $userRegionId ?? 0;

        /** @var Customer $customer */
        $customer = $this->objectManager->find(Customer::class, $customerId);
        $customerData = $customer->getActiveData();

        // check subregion
        $subregionJoint = $this->objectManager->getRepository(RegionSubregionJoint::class)->findOneBy(['region' => $customer->getRegion()]);
        if ($subregionJoint) {
            $subRegion = $subregionJoint->getSubregion()->getName();
        }

        // prepare region summary
        if ($customer->getRegion() && $userRegionId == $customer->getRegion()->getId()) {
            // same region
            $subTemplate = $this->objectManager->find(AddressBar::class, AddressBar::TYPE_BAR_REGION_SAME)->getValue();
            $regionSummary = $this->prepareData($customer, $customerData, $subTemplate);
        } else {
            // different region
            $subTemplate = $this->objectManager->find(AddressBar::class, AddressBar::TYPE_BAR_REGION_DIFFERENT)->getValue();

            // get users responsible for customer region
            if (!empty($customer->getRegion())) {
                $users = $this->objectManager->getRepository(User::class)->findBy(['region' => $customer->getRegion()]);
            }
            $regionUsers = [];
            if (!empty($users)) {
                foreach ($users as $user) {
                    $regionUsers[] = $user->getFullName();
                }
            }
            $regionSummary = $this->prepareData($customer, $customerData, $subTemplate);
            $regionSummary = str_replace(
                '[' . AddressBar::TAG_REGION_USERS . ']',
                $regionUsers ? implode(', ', $regionUsers) : '-',
                $regionSummary
            );
        }

        $template = $this->objectManager->find(AddressBar::class, AddressBar::TYPE_BAR_CUSTOMER)->getValue();
        $html = $this->prepareData($customer, $customerData, $template, $subRegion ?? null);

        return str_replace('[' . AddressBar::TAG_REGION_SUMMARY . ']', $regionSummary, $html);
    }

    /**
     * Gets html for user address bar
     *
     * @param int $userId
     * @return string
     */
    public function getBarUser(int $userId): string
    {
        $user = $this->objectManager->find(User::class, $userId);
        $template = $this->objectManager->find(AddressBar::class, AddressBar::TYPE_BAR_USER)->getValue();

        $html = str_replace(
            [
                '[' . AddressBar::TAG_USER_NAME . ']',
            ],
            [
                $user->getFullName(),
            ],
            $template
        );

        return $html;
    }

    /**
     * Substitutes template placeholders for customer data
     *
     * @param Customer     $customer
     * @param CustomerData $customerData
     * @param string       $template
     * @param string|null  $region
     * @return string
     */
    private function prepareData(
        Customer     $customer,
        CustomerData $customerData,
        string       $template,
        string       $region = null
    ): string
    {
        // check for payer
        if ($payer = $customer->getPayer()) {
            $payer = $payer->getNipAndName();
        }

        $phone = !empty($customer->getPhoneNumber())
            ? sprintf('<a href="tel:%s">%s</a>', $customer->getPhoneNumber(), $customer->getPhoneNumber())
            : '';
        $email = !empty($customer->getEmail())
            ? sprintf('<a href="mailto:%s">%s</a>', $customer->getEmail(), $customer->getEmail())
            : '';

        return str_replace(
            [
                '[' . AddressBar::TAG_CUSTOMER_CHAIN . ']',
                '[' . AddressBar::TAG_CUSTOMER_SUBCHAIN . ']',
                '[' . AddressBar::TAG_CUSTOMER_REGION . ']',
                '[' . AddressBar::TAG_CUSTOMER_SUBREGION . ']',
                '[' . AddressBar::TAG_CUSTOMER_NAME . ']',
                '[' . AddressBar::TAG_CUSTOMER_ZIPCODE . ']',
                '[' . AddressBar::TAG_CUSTOMER_CITY . ']',
                '[' . AddressBar::TAG_CUSTOMER_STREET . ']',
                '[' . AddressBar::TAG_CUSTOMER_STREET_NUMBER . ']',
                '[' . AddressBar::TAG_CUSTOMER_LOCAL . ']',
                '[' . AddressBar::TAG_CUSTOMER_SALE_STAGE . ']',
                '[' . AddressBar::TAG_CUSTOMER_FORMAT . ']',
                '[' . AddressBar::TAG_CUSTOMER_SUBFORMAT . ']',
                '[' . AddressBar::TAG_CUSTOMER_STATUS . ']',
                '[' . AddressBar::TAG_CUSTOMER_PHONE . ']',
                '[' . AddressBar::TAG_CUSTOMER_EMAIL . ']',
                '[' . AddressBar::TAG_PAYER . ']',
            ],
            [
                (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getChain()->getName(),
                (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getName(),
                (!$customer->getRegion()) ? '' : $customer->getRegion()->getName(),
                $region ?? '',
                $customerData->getName(),
                $customerData->getZipCode(),
                (!$customerData->getCity()) ? '' : $customerData->getCity()->getName(),
                $customerData->getStreetName(),
                $customerData->getStreetNumber(),
                $customerData->getLocalNumber(),
                $customer->getSaleStage() ? $customer->getSaleStage()->getName() : '-',
                $customer->getFormat() ? $customer->getFormat()->getName() : '',
                $customer->getSubformat() ? $customer->getSubformat()->getName() : '',
                $customer->getCustomerStatus() ? $customer->getCustomerStatus()->getName() : '',
                $phone, // TAG_CUSTOMER_PHONE
                $email, // TAG_CUSTOMER_EMAIL
                $payer ?? '',
            ],
            $template
        );
    }
}