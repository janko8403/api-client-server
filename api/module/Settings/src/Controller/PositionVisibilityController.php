<?php

namespace Settings\Controller;

use Configuration\Entity\Position;
use Doctrine\Persistence\ObjectManager;
use Settings\Form\CopyPrivilegesForm;
use Settings\Service\PositionVisibilityService;
use Hr\Controller\BaseController;

/**
 * Controller responsible for managing position visibility
 *
 * Dependencies:
 * - Doctrine\Persistence\ObjectManager
 * - Settings\Service\PositionVisibilityService
 *
 * @package Settings\Controller
 */
class PositionVisibilityController extends BaseController
{
    protected ObjectManager $objectManager;

    private PositionVisibilityService $positionVisibilityService;

    private CopyPrivilegesForm $copyPrivilegesForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager             $objectManager
     * @param PositionVisibilityService $positionVisibilityService
     * @param CopyPrivilegesForm        $copyPrivilegesForm
     */
    public function __construct(
        ObjectManager             $objectManager,
        PositionVisibilityService $positionVisibilityService,
        CopyPrivilegesForm        $copyPrivilegesForm
    )
    {
        $this->objectManager = $objectManager;
        $this->positionVisibilityService = $positionVisibilityService;
        $this->copyPrivilegesForm = $copyPrivilegesForm;
        $this->addBreadcrumbsPart('Ustawienia')->addBreadcrumbsPart('Widoczność stanowisk');
    }

    public function indexAction()
    {
        $positions = $this->objectManager->getRepository(Position::class)->findAll();
        $visibility = $this->positionVisibilityService->getAll();

        return ['positions' => $positions, 'visibility' => $visibility];
    }

    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->positionVisibilityService->save($data['visibility']);
            $this->flashMessenger()->addSuccessMessage('Ustawienia zostały zapisane');
        }

        return $this->redirect()->toRoute('settings/position-visibility');
    }

    public function copyAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->positionVisibilityService->copyPrivileges($data);
            $this->redirect()->toRoute('settings/position-visibility');
        }

        return ['form' => $this->copyPrivilegesForm];
    }
}