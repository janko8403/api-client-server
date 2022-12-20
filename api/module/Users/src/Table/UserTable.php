<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.10.17
 * Time: 10:48
 */

namespace Users\Table;


use Commissions\Entity\CommissionUser;
use Configuration\Object\ObjectService;
use Doctrine\Persistence\ObjectManager;
use Hr\Table\TableService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Users\Entity\User;

class UserTable extends TableService
{
    private ObjectManager $objectManager;

    public function __construct(
        ObjectService   $objectService,
        AbstractAdapter $cacheAdapter,
        ObjectManager   $objectManager
    )
    {
        parent::__construct($objectService, $cacheAdapter);
        $this->objectManager = $objectManager;
        $this->setIconSize('lg');
    }

    public function init()
    {
        $this->setId('tikrowUserTable');
        //$this->setOption(TableService::OPT_SHOW_EDIT, false);
        $this->configureColumns([
            'surname' => [
                'label' => 'Nazwisko',
                'sort' => 'u.surname', // sort by field (with alias)
            ],
            'name' => [
                'label' => 'Imię',
                'sort' => 'u.name',
            ],
            'login' => [
                'label' => 'login',
                'sort' => 'u.login',
            ],
            'email' => [
                'label' => 'Email',
            ],
            'phonenumber' => [
                'label' => 'Telefon',
            ],
            'position' => [
                'label' => 'Stanowisko',
                'value' => 'getPosition',
            ],
            'supervisor' => [
                'label' => 'Przełożony',
                'value' => 'getSupervisor',
            ],
            'referenceNumber' => [
                'label' => 'User SAP ID',
            ],
            'isactive' => [
                'label' => 'Aktywny',
                'value' => 'getIsActive',
                'helper' => 'chk',
            ],
        ]);
        $this->addRowAction(
            function ($record) {
                return [
                    'title' => 'Szczegóły',
                    'icon' => 'vcard-o',
                    'route' => 'users/details',
                    'class' => 'a-details',
                    'route-params' => ['action' => 'details', 'id' => $record->getId()],
                ];
            }
        );
        $this->addRowAction(
            function ($record) {
                return [
                    'title' => 'Zmiana hasła',
                    'icon' => 'key',
                    'route' => 'users/change-password/user',
                    'class' => 'a-changePassword',
                    'route-params' => ['action' => 'user', 'id' => $record->getId()],
                ];
            }
        );

        $this->setActivationActions('users/default');
    }

    public function getPosition(User $user): string
    {
        return ($user->getConfigurationPosition()) ? $user->getConfigurationPosition()->getName() : '';
    }

    public function getSupervisor(User $user): string
    {
        return ($user->getSupervisor()) ? $user->getSupervisor()->getFullName() : '';
    }

    public function getIsActive(User $user)
    {
        return $user->getIsactive();
    }

    public function getRegion(User $user): string
    {
        return $user->getRegion() ? $user->getRegion()->getName() : '';
    }
}