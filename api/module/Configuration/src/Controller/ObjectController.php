<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 04.07.17
 * Time: 16:14
 */

namespace Configuration\Controller;


use Configuration\Entity\ObjectGroup;
use Configuration\Entity\ObjectField;
use Configuration\Entity\ObjectFieldDetail;
use Configuration\Entity\Position;
use Configuration\ObjectField\ObjectFieldService;
use Configuration\ObjectFieldDetail\ObjectFieldDetailService;
use Doctrine\Persistence\ObjectManager;
use Settings\Service\CacheService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Model\ViewModel;

class ObjectController extends AbstractActionController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var ObjectFieldDetail
     */
    private $objectFieldDetailService;

    /**
     * @var ObjectFieldService
     */
    private $objectFieldService;


    /**
     * ObjectController constructor.
     * @param ObjectManager $objectManager
     * @param PhpRenderer $renderer
     * @param ObjectFieldDetailService $objectFieldDetailService
     * @param ObjectFieldService $objectFieldService
     */
    public function __construct(
        ObjectManager $objectManager,
        PhpRenderer $renderer,
        ObjectFieldDetailService $objectFieldDetailService,
        ObjectFieldService $objectFieldService
    )
    {
        $this->objectManager = $objectManager;
        $this->renderer = $renderer;
        $this->objectFieldDetailService = $objectFieldDetailService;
        $this->objectFieldService = $objectFieldService;
    }

    public function editVisibilityAction(){
        $objectId = $this->params('id');

        $object = $this->objectManager->getRepository(ObjectGroup::class)->find($objectId);
        $positions = $this->objectManager->getRepository(Position::class)->findBy(['isActive' => 1]);
        $objectFields = $this->objectManager->getRepository(ObjectField::class)->findBy(['object' => $object], ['sequence' => 'ASC']);

        $vm = new ViewModel([
            'object' => $object,
            'positions' => $positions,
            'data' => $this->objectFieldDetailService->getObjectFieldDetails($objectFields),
        ]);

        $vm->setTemplate('configuration/object/visibility');

        return new JsonModel([
            'html' => $this->renderer->render($vm),
        ]);
    }

    public function saveVisibilityAction(){
        $this->objectFieldDetailService->saveObjectFieldVisibility($this->getRequest()->getPost());
        $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);

        return new JsonModel([
            'result' => true,
        ]);
    }


    public function editSequenceAction(){
        $objectId = $this->params('id');
        /**
         * @var ObjectGroup
         */
        $object = $this->objectManager->getRepository(ObjectGroup::class)->find($objectId);
        $objectFields = $this->objectManager->getRepository(ObjectField::class)->findBy(['object' => $object], ['sequence' => 'ASC']);
        $vm = new ViewModel([
            'object' => $object,
            'fields' => $objectFields,
        ]);

        $vm->setTemplate('configuration/object/sequence');

        return new JsonModel([
            'html' => $this->renderer->render($vm),
            'result' => (!empty($object) && count($object->getFields())) ? true : false,
        ]);
    }

    public function saveSequenceAction(){
        $this->objectFieldService->saveSequence($this->getRequest()->getPost());
        $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);

        return new JsonModel([
            'result' => true,
        ]);
    }
}