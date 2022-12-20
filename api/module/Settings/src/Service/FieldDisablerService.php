<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 09.11.2018
 * Time: 14:32
 */

namespace Settings\Service;

use Customers\Entity\Customer;
use Customers\Form\CustomerEditForm;
use Doctrine\Persistence\ObjectManager;

class FieldDisablerService
{
    private PositionVisibilityService $visibilityService;

    private array $identity;

    private ?int $recordId;

    private ObjectManager $objectManager;

    /**
     * FieldDisablerService constructor.
     *
     * @param PositionVisibilityService $visibilityService
     * @param ObjectManager             $objectManager
     * @param array                     $identity
     * @param int|null                  $recordId
     */
    public function __construct(
        PositionVisibilityService $visibilityService,
        ObjectManager $objectManager,
        array $identity,
        ?int $recordId
    )
    {
        $this->visibilityService = $visibilityService;
        $this->identity = $identity;
        $this->recordId = $recordId;
        $this->objectManager = $objectManager;
    }

    public function shouldDisableAllFields(string $formClass): bool
    {
        if (empty($this->recordId)) {
            return false;
        }

        switch ($formClass) {
            case CustomerEditForm::class:
                $customer = $this->objectManager->find(Customer::class, $this->recordId);

                if (!$customer->getRegion()) {
                    return true;
                }

                return !$this->visibilityService->checkUserCanEditCustomer(
                    $this->identity['configurationPositionId'],
                    $this->identity['regionDicId'],
                    $customer->getRegion()->getId()
                );
            default:
                return false;
        }
    }
}