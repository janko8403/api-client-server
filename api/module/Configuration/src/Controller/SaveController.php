<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.06.17
 * Time: 15:53
 */

namespace Configuration\Controller;


use Configuration\Form\ResourceForm;
use Configuration\Resource\ResourceService;
use Doctrine\Persistence\ObjectManager;
use Settings\Service\CacheService;
use Hr\Controller\BaseController;
use \Laminas\Mvc\I18n\Translator;
use Configuration\Entity\Resource;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

class SaveController extends BaseController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ResourceForm
     */
    private $resourceForm;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var ResourceService
     */
    private $resourceService;

    /**
     * @var PhpRenderer
     */

    private $renderer;

    /**
     * SaveController constructor.
     *
     * @param ObjectManager   $objectManager
     * @param ResourceForm    $resourceForm
     * @param Translator      $translator
     * @param ResourceService $resourceService
     * @param PhpRenderer     $renederer
     */

    public function __construct(
        ObjectManager   $objectManager,
        ResourceForm    $resourceForm,
        Translator      $translator,
        ResourceService $resourceService,
        PhpRenderer     $renederer
    )
    {
        $this->objectManager = $objectManager;
        $this->resourceForm = $resourceForm;
        $this->translator = $translator;
        $this->resourceService = $resourceService;
        $this->renderer = $renederer;
        $this->addBreadcrumbsPart('Konfiguracja');
    }

    public function addAction()
    {
        $this->addBreadcrumbsPart('Dodaj');

        $resource = new Resource();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->resourceForm->bind($resource);
            $this->resourceForm->setData($data);

            if ($this->resourceForm->isValid($data, $data)) {
                $this->resourceService->saveResource($resource);
                $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Rekord został zapisany.'));
                $this->redirect()->toRoute('configuration');
            }
        }

        return ['form' => $this->resourceForm];
    }

    public function editAction()
    {
        $id = $this->params('id');

        $this->addBreadcrumbsPart('Edycja');

        $resource = $this->objectManager->getRepository(Resource::class)->find($id);

        $this->resourceForm->bind($resource);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->resourceForm->bind($resource);
            $this->resourceForm->setData($data);

            if ($this->resourceForm->isValid($data, $data)) {
                $this->objectManager->persist($resource);
                $this->objectManager->flush();
                $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Rekord został zapisany'));
                $this->redirect()->toRoute('configuration', [], ['query' => ['clear-cache' => 1]]);
            }
        }

        $vm = new ViewModel(['form' => $this->resourceForm]);

        $vm->setTemplate('configuration/save/add');

        return $vm;
    }

    public function subresourceEditAction()
    {
        $id = $this->params('id');

        /**
         * @var \Configuration\Entity\Resource
         */
        $resource = $this->objectManager->getRepository(Resource::class)->find($id);
        $this->resourceForm->setAttribute('action', 'configuration/subresourceEdit/' . $resource->getId());
        $this->resourceForm->bind($resource);
        $this->resourceForm->remove('save');

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->resourceForm->bind($resource);
            $this->resourceForm->setData($data);

            if ($this->resourceForm->isValid()) {

                $this->objectManager->persist($resource);
                $this->objectManager->flush();
                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Rekord został zapisany'));

                $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);
                if (!empty($resource->getParent())) {
                    $subMenu = $this->objectManager->getRepository(Resource::class)->findBy(['parent' => $resource->getParent()->getId()]);
                    $vm = new ViewModel([
                        'submenu' => $subMenu,
                        'resourceId' => $id,
                    ]);
                    $vm->setTemplate('configuration/resource/submenu');
                    $vm->setTerminal(true);

                    return new JsonModel(['result' => true, 'html' => $this->renderer->render($vm)]);
                } else {
                    $vm = new ViewModel([
                        'redirect' => true,
                    ]);
                    $vm->setTemplate('configuration/save/add');
                    $vm->setTerminal(true);

                    return new JsonModel([
                        'redirect' => '/configuration',
                    ]);
                }
            }
        }

        $vm = new ViewModel(['form' => $this->resourceForm]);
        $vm->setTemplate('configuration/save/add');
        $vm->setTerminal(true);

        return new JsonModel([
            'html' => $this->renderer->render($vm),
            'result' => false,
        ]);
    }
}