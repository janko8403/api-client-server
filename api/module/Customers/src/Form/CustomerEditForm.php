<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 07.09.17
 * Time: 18:10
 */

namespace Customers\Form;

use Customers\Entity\CustomerGroup;
use Customers\Fieldset\CustomerDataFieldset;
use Customers\Fieldset\CustomerFieldset;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Form\PositionVisibilityAwareInterface;
use Hr\Form\RecordForm;
use Laminas\Form\Element\Collection;
use Settings\Entity\PositionVisibility;
use Settings\Service\PositionVisibilityService;

class CustomerEditForm extends RecordForm implements PositionVisibilityAwareInterface
{
    public function __construct(
        ObjectManager             $objectManager,
        PositionVisibilityService $positionVisibilityService,
        array                     $identity
    )
    {
        parent::__construct('customerEditForm');
        $this->setPositionVisibilityService($positionVisibilityService);
        $this->setIdentity($identity);

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager));

        $regions = $this->getRegionsVisibility(PositionVisibility::FIELD_CUSTOMERS_EDIT);

        $customerFieldset = new CustomerFieldset($objectManager, $regions);
        $customerFieldset->add([
            'type' => Collection::class,
            'name' => 'customerData',
            'options' => [
                'count' => 1,
                'should_create_template' => false,
                'allow_add' => true,
                'target_element' => new CustomerDataFieldset($objectManager),
            ],
        ]);
        $customerFieldset->setUseAsBaseFieldset(true);
        $this->add($customerFieldset);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz',
            ],
        ]);
    }

    public function populateValues($data, $onlyBase = false): void
    {
        // override customer groups hydration
        $cgs = [];

        if (isset($data['customer']['customerGroups'])) {
            foreach ($data['customer']['customerGroups'] as $cg) {
                if ($cg instanceof CustomerGroup) {
                    $cgs[] = $cg->getCustomerGroup()->getId();
                } else {
                    $cgs[] = $cg;
                }
            }
        }
        $data['customer']['customerGroups'] = $cgs;

        parent::populateValues($data, $onlyBase);
    }
}