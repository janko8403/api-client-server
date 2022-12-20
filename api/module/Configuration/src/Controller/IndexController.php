<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 27.06.17
 * Time: 15:48
 */

namespace Configuration\Controller;

use Configuration\Form\MenuSearchFrom;
use Configuration\Repository\ResourceRepository;
use Doctrine\Persistence\ObjectManager;
use Settings\Service\CacheService;
use Hr\Controller\BaseController;
use Hr\Table\TableService;
use Configuration\Entity\Resource;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends BaseController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var MenuSearchFrom
     */
    private $menuSearchForm;

    /**
     * IndexController constructor.
     *
     * @param ObjectManager  $objectManager
     * @param MenuSearchFrom $menuSearchFrom
     */
    public function __construct(
        ObjectManager  $objectManager,
        MenuSearchFrom $menuSearchFrom
    )
    {
        $this->objectManager = $objectManager;
        $this->menuSearchForm = $menuSearchFrom;
        $this->addBreadcrumbsPart('Konfiguracja')->addBreadcrumbsPart('Lista');
    }

    public function indexAction()
    {
        if ($this->params()->fromQuery('clear-cache')) {
            $this->getEventManager()->trigger(CacheService::EVENT_CLEAR);
        }

        $params = $this->params()->fromQuery();
        $menuData = $this->objectManager->getRepository(Resource::class)->search($params)->execute();
        $this->menuSearchForm->setData($params);

        return ['menu' => $menuData, 'search' => $this->menuSearchForm];
    }

}