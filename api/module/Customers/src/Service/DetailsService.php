<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 29.01.18
 * Time: 17:32
 */

namespace Customers\Service;


use Configuration\Entity\Resource;
use Doctrine\Persistence\ObjectManager;
use Hr\Acl\AclService;

class DetailsService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var AclService
     */
    private $aclService;

    private $configurationPositionId;

    public function __construct(
        ObjectManager $objectManager,
        AclService    $aclService,
                      $configurationPositionId
    )
    {
        $this->objectManager = $objectManager;
        $this->aclService = $aclService;
        $this->configurationPositionId = $configurationPositionId;
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function getCustomerMenu(): array
    {
        $userMenuPositions = $this->objectManager->getRepository(Resource::class)->getCustomerMenu();
        $customerMenu = [];

        /** @var Resource $menu */

        foreach ($userMenuPositions as $key => $menu) {
            if ($this->aclService->getAcl()->isAllowed($this->configurationPositionId, $menu->getName())) {
                $customerMenu[$key]['label'] = $menu->getLabel();
                $customerMenu[$key]['route'] = $menu->getRoute();
            }
        }

        return $customerMenu;
    }

}