<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.06.17
 * Time: 15:29
 */

namespace Configuration\Controller;


use Configuration\Entity\ObjectGroup;
use Configuration\Entity\Position;
use Configuration\Entity\Resource;
use Configuration\Entity\ResourcePosition;
use Configuration\Resource\ResourceService;
use Doctrine\Persistence\ObjectManager;
use Settings\Service\CacheService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

class ResourceController extends AbstractActionController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ResourceService
     */
    private $resourceService;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * ResourceController constructor.
     * @param ObjectManager $objectManager
     * @param ResourceService $resourceService
     * @param PhpRenderer $renderer
     */
    public function __construct(
        ObjectManager $objectManager,
        ResourceService $resourceService,
        PhpRenderer $renderer
    ) {
        $this->objectManager = $objectManager;
        $this->resourceService = $resourceService;
        $this->renderer = $renderer;
    }

    public function resourcePositionsAction()
    {
        $id = $this->params('id');

        $positions = $this->objectManager->getRepository(Position::class)->findAll();
        $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);

        $vm = new ViewModel([
            'positions' => $positions,
            'resourceId' => $id,
            'data' => $this->resourceService->getPositionsData($id)
        ]);
        $vm->setTerminal(true);

        $vm->setTemplate('configuration/resource/resource-positions');
        return new JsonModel([
            'html' => $this->renderer->render($vm),
            'result' => true
        ]);
    }

    public function saveResourcePositionsAction()
    {
        $resourceId = $this->params('id');
        $answers = $this->params()->fromPost()['answers'];

        $this->resourceService->saveResourceVisibilityForPositions($answers, $resourceId);
        $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);

        return new JsonModel(['result' => true]);
    }

    public function submenuAction()
    {
        $id = $this->params('id');

        $subMenu = $this->objectManager->getRepository(Resource::class)->findBy(['parent' => $id], ['sequence' => 'ASC']);
        if (count($subMenu) == 0) {
            /**
             * @var \Configuration\Entity\Resource
             */
            $resource = $this->objectManager->getRepository(Resource::class)->find($id);

            $objects = $resource->getObjects();
            $vm = new ViewModel([
                'objects' => $objects,
                'resourceId' => $id,
            ]);
            $vm->setTemplate('configuration/resource/objects');
        } else {
            $vm = new ViewModel([
                'submenu' => $subMenu,
                'resourceId' => $id,
            ]);
            $vm->setTemplate('configuration/resource/submenu');
        }

        $result = (count($subMenu) > 0 || isset($objects) && count($objects)) ? true : false;

        return new JsonModel([
            'html' => $this->renderer->render($vm),
            'result' => $result,
        ]);
    }
}