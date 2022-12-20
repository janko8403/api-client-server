<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.12.2016
 * Time: 17:32
 */

namespace Hr\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger;

/**
 * Base controller for applications. Provides breadcrumbs array for creating path on the top of the page.
 *
 * @method array|null identity() \Laminas\Mvc\Plugin\Identity\Identity->__invoke()
 * @method mixed jsonContent() \Hr\Mvc\Controller\Plugin\JsonContent->__invoke()
 * @method FlashMessenger|PluginFlashMessenger flashMessenger() FlashMessenger->__invoke()
 * @package Hr\Controller
 */
class BaseController extends AbstractActionController
{
    private array $breadcrumbs = [];

    public function addBreadcrumbsPart(string $name): BaseController
    {
        $this->breadcrumbs[] = $name;
        return $this;
    }

    public function addBreadcrumbsLink(string $route, string $label, array $params = []): BaseController
    {
        $this->breadcrumbs[] = [
            'route' => $route,
            'label' => $label,
            'params' => $params,
        ];
        return $this;
    }

    public function onDispatch(MvcEvent $e)
    {
        $result = parent::onDispatch($e);
        $this->layout()->setVariable('breadcrumbs', $this->breadcrumbs);
        return $result;
    }

    /**
     * Activates/deactivates record.
     *
     * @param bool   $active
     * @param string $class
     * @return \Laminas\Stdlib\ResponseInterface
     */
    protected function activation(bool $active, string $class)
    {
        $id = $this->params('id');
        if ($this->getRequest()->isPost()) {
            $record = $this->objectManager->find($class, $id);
            $record->setIsActive($active);
            $this->objectManager->persist($record);
            $this->objectManager->flush();
            $this->getResponse()->setContent('ok');
        }

        return $this->getResponse();
    }
}