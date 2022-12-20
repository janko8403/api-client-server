<?php

namespace Customers\Table;

use Configuration\Object\ObjectService;
use Customers\Entity\Customer;
use Hr\Table\TableService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;

class CustomerTable extends TableService
{
    /**
     * CustomerTable constructor.
     *
     * @param ObjectService   $objectService
     * @param AbstractAdapter $cacheAdapter
     */
    public function __construct(ObjectService $objectService, AbstractAdapter $cacheAdapter)
    {
        parent::__construct($objectService, $cacheAdapter);
    }

    public function init()
    {
        $this->setId('customerTable');
        //  $this->setOption(TableService::OPT_SHOW_EDIT, false);
        $this->configureColumns([
            'name' => [
                'label' => 'Nazwa',
                'value' => 'getName',
                'sort' => 'cd.name', // sort by field (with alias)
            ],
            'innerId' => [
                'label' => 'Wewnętrzne Id',
                'value' => 'getInnerId',
                'sort' => 'c.innerCustomerId',
            ],
            'chain' => [
                'label' => 'Sieć',
                'value' => 'getChain',
                'sort' => 'ch.name',
            ],
            'subChain' => [
                'label' => 'Podsieć',
                'value' => 'getSubchain',
                'sort' => 'sc.name',
            ],
            'visitFrequency' => [
                'label' => 'Częstotliwość wizyt',
                'value' => 'getVisitFrequency',
                'sort' => 'ddvf.name',
            ],
            'region' => [
                'label' => 'Region',
                'value' => 'getRegion',
                'sort' => 'ddr.name',
            ],
            'size' => [
                'label' => 'Rozmiar',
                'value' => 'getSize',
                'sort' => 'dds.name',
            ],
            'subsize' => [
                'label' => 'Podrozmiar',
                'value' => 'getSubsize',
                'sort' => 'dds.name',
            ],
            'priority' => [
                'label' => 'Priorytet',
                'value' => 'getPriority',
                'sort' => 'ddp.name',
            ],
            'format' => [
                'label' => 'Format',
                'value' => 'getFormat',
                'sort' => 'ddf.name',
            ],
            'subformat' => [
                'label' => 'Podformat',
                'value' => 'getSubformat',
                'sort' => 'ddf.name',
            ],
            'street' => [
                'label' => 'Ulica',
                'value' => 'getStreet',
                'sort' => 'cd.streetName',
            ],
            'zipCode' => [
                'label' => 'Kod pocztowy',
                'value' => 'getZipCode',
                'sort' => 'cd.zipCode',
            ],
            'city' => [
                'label' => 'Miasto',
                'value' => 'getCity',
                'sort' => 'ddc.name',
            ],
            'nip' => [
                'label' => 'NIP',
                'value' => 'getNIP',
                'sort' => 'cd.nip',
            ],
            'payer' => [
                'label' => 'Płatnik',
                'value' => 'getPayer',
                'sort' => 'pd.name',
            ],
            'payerNip' => [
                'label' => 'Nip płatnika',
                'value' => 'getPayerNip',
                'sort' => 'pd.nip',
            ],
            'logisticRegion' => [
                'label' => 'Region Logistyczny',
                'value' => 'getLogisticRegion',
                'sort' => 'ddlr.name',
            ],
            'customerStatus' => [
                'label' => 'Status klienta',
                'value' => 'getCustomerStatus',
                'sort' => 'c.customerStatus',
            ],
            'creator' => [
                'label' => 'Utworzył',
                'value' => 'getCreator',
                'sort' => 'u.creator',
            ],
            'creationDate' => [
                'label' => 'Data utworzenia',
                'value' => 'getCreationDate',
                //'helper' => ['name' => 'dt', 'params' => [DateTime::DATE]],
                'sort' => 'c.creationDate',
            ],
            'email' => [
                'label' => 'email',
                'sort' => 'c.email',
            ],
            'phoneNumber' => [
                'label' => 'Numer Telefonu',
                'sort' => 'c.phoneNumber',
            ],
            'maxContactDate' => [
                'label' => 'Maksymalna data kontaktu',
                'value' => 'getMaxContactDate',
                //'helper' => ['name' => 'dt', 'params' => [DateTime::DATE]],
                'sort' => 'c.maxContactDate',
            ],
            'additionalInformation' => [
                'label' => 'Informacje dodatkowe',
                'sort' => 'c.additionalInformation',
            ],
            'modificationDate' => [
                'label' => 'Data modyfikacji',
                'value' => 'getModificationDate',
                'sort' => 'cd.modificationDate',
            ],
            'regon' => [
                'label' => 'REGON',
                'value' => 'getRegon',
                'sort' => 'cd.regon',
            ],
            'customerGroups' => [
                'label' => 'Grypu klienta',
                'value' => 'getCustomerGroups',
            ],
            'saleStage' => [
                'label' => 'Etap Sprzedaży',
                'value' => 'getCustomersSaleStage',
            ],
            'saleStageDateChange' => [
                'label' => 'Data zmiany etapu sprzedaży',
                'value' => 'getSaleStageDateChange',
            ],
        ]);
        $this->addRowAction(
            function ($record) {
                return [
                    'title' => 'Użytkownicy',
                    'icon' => 'users',
                    'route' => 'customers/users',
                    'route-params' => ['action' => 'index', 'id' => $record->getId()],
                ];
            }
        );
        $this->setActivationActions('customers/default');
    }

    public function getCreationDate(Customer $customer)
    {
        return ($customer->getCreationDate()) ? $customer->getCreationDate()->format('Y-m-d H:i:s') : '';
    }

    public function getMaxContactDate(Customer $customer)
    {
        return ($customer->getMaxContactDate()) ? $customer->getMaxContactDate()->format('Y-m-d') : '';
    }

    public function getStreet(Customer $customer)
    {
        return $customer->getCustomerData()->first()->getFullStreetName();
    }

    public function getZipCode(Customer $customer)
    {
        return $customer->getCustomerData()->first()->getZipCode();
    }

    public function getCity(Customer $customer)
    {
        if ($customer->getCustomerData()->first()->getCity()) {
            return $customer->getCustomerData()->first()->getCity()->getName();
        }
    }

    public function getName(Customer $customer)
    {
        return $customer->getCustomerData()->first()->getName();
    }

    public function getInnerId(Customer $customer)
    {
        return $customer->getInnerCustomerId();
    }

    public function getChain(Customer $customer)
    {
        return (!$customer->getSubchain()) ? '' : $customer->getSubchain()->getChain()->getName();
    }

    public function getSubchain(Customer $customer)
    {
        if ((!$customer->getSubchain())) {
            return '';
        } else {
            return $customer->getSubchain()->getName();
        }

    }

    public function getRegion(Customer $customer)
    {
        return (!$customer->getRegion()) ? '' : $customer->getRegion()->getName();
    }

    public function getSize(Customer $customer)
    {
        return (!$customer->getSize()) ? '' : $customer->getSize()->getName();
    }

    public function getSubsize(Customer $customer)
    {
        return (!$customer->getSubsize()) ? '' : $customer->getSubsize()->getName();
    }

    public function getPriority(Customer $customer)
    {
        return (!$customer->getPriority()) ? '' : $customer->getPriority()->getName();
    }

    public function getFormat(Customer $customer)
    {
        return (!$customer->getFormat()) ? '' : $customer->getFormat()->getName();
    }

    public function getSubformat(Customer $customer)
    {
        return (!$customer->getSubformat()) ? '' : $customer->getSubformat()->getName();
    }

    public function getPayerNip(Customer $customer)
    {
        return (!$customer->getPayer()) ? '' : $customer->getPayer()->getActiveData()->getNip();
    }

    public function getPayer(Customer $customer)
    {
        return (!$customer->getPayer()) ? '' : $customer->getPayer()->getActiveData()->getName();
    }

    public function getNIP(Customer $customer)
    {
        return $customer->getCustomerData()->first()->getNip();
    }

    public function getVisitFrequency(Customer $customer)
    {
        return (!$customer->getVisitsFrequency()) ? '' : $customer->getVisitsFrequency()->getName();
    }

    public function getLogisticRegion(Customer $customer)
    {
        return (!$customer->getLogisticRegion()) ? '' : $customer->getLogisticRegion()->getName();
    }

    public function getCustomerStatus(Customer $customer)
    {
        return (!$customer->getCustomerStatus()) ? '' : $customer->getCustomerStatus()->getName();
    }

    public function getCreator(Customer $customer)
    {
        return (!$customer->getCreator()) ? '' : $customer->getCreator()->getFullName();
    }

    public function getReportedStatus(Customer $customer)
    {
        return Customer::getReportedStatus()[$customer->getReported()];
    }

    public function getModificationDate(Customer $customer)
    {
        return $customer->getCustomerData()->first()->getModificationDate()->format('Y-m-d H:i:s');
    }

    public function getRegon(Customer $customer)
    {
        return $customer->getCustomerData()->first()->getRegon();
    }

    public function getCustomersSaleStage(Customer $customer)
    {
        return (!$customer->getSaleStage()) ? '' : $customer->getSaleStage()->getName();
    }

    public function getCustomerGroups(Customer $customer)
    {
        $data = [];

        foreach ($customer->getCustomerGroups() as $g) {
            $data[] = $g->getCustomerGroup()->getName();
        }

        return implode('<br />', $data);
    }

    public function getSaleStageDateChange(Customer $customer)
    {
        return $customer->getSaleStageDateChange() ? $customer->getSaleStageDateChange()->format('Y-m-d') : '-';
    }
}