<?php

namespace AssemblyOrders\Table;

use AssemblyOrders\Entity\AssemblyOrder;
use Configuration\Object\ObjectService;
use Hr\Table\TableService;
use Hr\View\Helper\DateTime;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;

class AssemblyOrderTable extends TableService
{
    /**
     * AssemblyOrderTable constructor.
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
        $this->setId('assemblyOrdersTable');
        $this->configureColumns([
            'accepted' => [
                'label' => 'Przyjęte',
                'value' => 'getAccepted',
                'helper' => 'chk',
            ],
            'address' => [
                'label' => 'Montaż',
                'value' => 'getAddress',
            ],
            'acceptedUser' => [
                'label' => 'Montażysta',
                'value' => 'getUser',
            ],
            'customer' => [
                'label' => 'Sklep',
                'value' => 'getCustomer',
            ],
            'measurementStatus' => [
                'label' => 'measurementStatus',
            ],
            'installationStatus' => [
                'label' => 'installationStatus',
            ],
            'assemblyCreatorName' => [
                'label' => 'assemblyCreatorName',
            ],
            'creationDateTime' => [
                'label' => 'creationDateTime',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME_SHORT]],
            ],
            'deliveryDateTime' => [
                'label' => 'deliveryDateTime',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME_SHORT]],
            ],
            'expectedContactDateTime' => [
                'label' => 'expectedContactDateTime',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME_SHORT]],
            ],
            'expectedInstallationDateTime' => [
                'label' => 'expectedInstallationDateTime',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME_SHORT]],
            ],
            'acceptedInstallationDateTime' => [
                'label' => 'acceptedInstallationDateTime',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME_SHORT]],
            ],
            'estimatedCostNet' => [
                'label' => 'estimatedCostNet',
            ],
        ]);
        $this->addRowAction(
            function ($record) {
                return [
                    'title' => 'Ranking',
                    'icon' => 'users',
                    'route' => 'assembly-orders/rankings',
                    'route-params' => ['id' => $record->getId()],
                ];
            }
        );
    }

    public function getAddress(AssemblyOrder $order): string
    {
        return sprintf(
            "%s<br>%s %s %s",
            $order->getInstallationName(),
            $order->getInstallationAddress(),
            $order->getInstallationZipCode(),
            $order->getInstallationCity()
        );
    }

    public function getUser(AssemblyOrder $order): string
    {
        return (string)$order->getAccepted()?->getUser()->getFullName();
    }

    public function getAccepted(AssemblyOrder $order): string
    {
        return !!$order->getAccepted();
    }

    public function getCustomer(AssemblyOrder $order): string
    {
        $data = $order->getCustomer()->getActiveData();

        return sprintf(
            "%s<br>%s",
            $data->getName(),
            $data->getFullAddress()
        );
    }
}