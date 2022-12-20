<?php

namespace Users\Service;


use Configuration\Entity\Resource;
use Doctrine\Persistence\ObjectManager;
use Hr\Acl\AclService;

class DetailsService
{
    private ObjectManager $objectManager;

    private AclService $aclService;

    public function __construct(ObjectManager $objectManager, AclService $aclService)
    {
        $this->objectManager = $objectManager;
        $this->aclService = $aclService;
    }

    /**
     * Gets user menu options.
     *
     * @param int $configurationPositionId
     * @return array
     */
    public function getUserMenu(int $configurationPositionId): array
    {
        $userMenuPositions = $this->objectManager->getRepository(Resource::class)->getUserDetailsMenu();
        $userMenu = [];

        /** @var Resource $menu */
        foreach ($userMenuPositions as $key => $menu) {
            if ($this->aclService->getAcl()->isAllowed($configurationPositionId, $menu->getName())) {
                $userMenu[$key]['label'] = $menu->getLabel();
                $userMenu[$key]['route'] = $menu->getRoute();
            }
        }

        return $userMenu;
    }

}