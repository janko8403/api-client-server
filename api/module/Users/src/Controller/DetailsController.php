<?php


namespace Users\Controller;


use Doctrine\Persistence\ObjectManager;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;
use MonitoringFulfilments\Fulfilment\FulfilmentService;
use MonitoringFulfilments\FulfilmentCheck\User\CheckerService;
use Hr\Controller\BaseController;
use Users\Entity\User;
use Users\Service\DetailsService;

class DetailsController extends BaseController
{
    private ObjectManager $objectManager;

    private FulfilmentService $fulfilmentService;

    private CheckerService $checkerService;

    private DetailsService $detailsService;

    /**
     * DetailsController constructor.
     *
     * @param ObjectManager     $objectManager
     * @param FulfilmentService $fulfilmentService
     * @param CheckerService    $checkerService
     * @param DetailsService    $detailsService
     */
    public function __construct(
        ObjectManager     $objectManager,
        FulfilmentService $fulfilmentService,
        CheckerService    $checkerService,
        DetailsService    $detailsService
    )
    {
        $this->objectManager = $objectManager;
        $this->fulfilmentService = $fulfilmentService;
        $this->checkerService = $checkerService;
        $this->detailsService = $detailsService;
        $this->addBreadcrumbsPart('Użytkownicy');
    }

    public function indexAction()
    {
        $container = new Container('users');
        $container->return_url = $this->getRequest()->getRequestUri();

        $id = $this->params('id');
        $user = $this->objectManager->find(User::class, $id);

        if ($user) {
            $this->addBreadcrumbsPart($user->getFullName());

            $monitoringGroups = $this->fulfilmentService->getMonitoringGroupsForUser($user, $this->checkerService);
            $userMenu = $this->detailsService->getUserMenu($this->identity()['configurationPositionId']);
            $this->clearSession();

            return new ViewModel([
                'user' => $user,
                'checkerService' => $this->checkerService,
                'monitoringGroups' => $monitoringGroups,
                'userMenu' => $userMenu,
            ]);
        } else {
            throw new \Exception("Cannot find user");
        }
    }

    private function clearSession()
    {
        (new Container('fulfilment'))->getManager()->getStorage()->clear('fulfilment');
    }
}