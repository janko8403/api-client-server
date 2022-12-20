<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 25.07.17
 * Time: 18:39
 */

namespace Customers\Controller;

use Customers\Entity\Customer;
use Customers\Form\CustomerEditForm;
use Customers\Form\CustomerForm;
use Customers\Repository\CustomerRepository;
use Customers\Service\CustomerService;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

class SaveController extends BaseController
{

    public function __construct(
        private ObjectManager    $objectManager,
        private CustomerForm     $customerForm,
        private CustomerService  $customerService,
        private CustomerEditForm $customerEditForm
    )
    {
    }

    public function addAction(): ViewModel
    {
        $this->addBreadcrumbsPart('Klienci')->addBreadcrumbsPart('Dodaj');

        $data = [];
        $customer = new Customer();
        $this->customerForm->remove('isActive');
        $vm = new ViewModel(['form' => $this->customerForm]);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->customerForm->bind($customer);
            $this->customerForm->setData($data);
            if ($this->customerForm->isValid()) {
                $this->customerService->setDataAfterValidate($customer, $this->customerForm, $data);
                $this->customerService->add($customer, $data);

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany. ');
                $this->redirect()->toRoute('customers');
            } else {
                $vm->setVariable('autocomplete_values', [
                    'city' => $data['customer']['customerData'][0]['city'] ?? null,

                ]);
                $vm->setVariable('autocomplete2', [
                    'payer' => $data['customer']['payer'] ?? null,

                ]);
            }
        } else {
            [$data, $cityId] = $this->customerService->setDefaultData($data);
            $this->customerForm->setData($data);
            $vm->setVariable('autocomplete_values', [
                'city' => $cityId ?? null,
            ]);
            $vm->setVariable('autocomplete2', [
                'payer' => $data['customer']['payer'] ?? null,
            ]);
        }

        return $vm;
    }

    public function editAction(): ViewModel
    {

        $vm = new ViewModel(['form' => $this->customerEditForm]);
        $vm->setTemplate('customers/save/add');
        /** @var CustomerRepository $customerRepository */
        $customerRepository = $this->objectManager->getRepository(Customer::class);
        /** @var Customer $customer */
        $customer = $customerRepository->getCustomerWithActiveData($this->params('id'));
        $customerData = clone $customer->getActiveData();
        $this->customerEditForm->bind($customer);
        $this->addBreadcrumbsPart($customerData->getName());

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->customerEditForm->setData($data);

            if ($this->customerEditForm->isValid()) {
                $this->customerService->edit($customer, $data, $customerData);

                $this->flashMessenger()->addSuccessMessage('Rekord został zapisany.');

                // return to previous screen
                $container = new Container('customers');
                $url = $container->return_url ?? '/customers';
                $this->redirect()->toUrl($url);
            } else {
                $vm->setVariable('autocomplete_values', [
                    'city' => $data['customer']['customerData'][0]['city'] ?? null,
                ]);
                $vm->setVariable('autocomplete2', [
                    'payer' => $data['customer']['payer'] ?? null,

                ]);
            }
        } else {
            $vm->setVariable('autocomplete_values', [
                'city' => ($customer->getCustomerData()[0]->getCity()) ? $customer->getCustomerData()[0]->getCity()->getId() : null,
            ]);
        }

        return $vm;
    }
}