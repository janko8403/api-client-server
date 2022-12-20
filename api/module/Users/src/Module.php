<?php

namespace Users;

use Doctrine\Persistence\ObjectManager;
use Hr\Authentication\AuthenticationService;
use Laminas\EventManager\EventInterface;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Stdlib\DispatchableInterface;
use Users\Entity\User;
use Users\Service\EventService;
use Users\Service\SocialSecurityService;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    const EVENT_CREATE_USER_EVENT = 'eventCreateUserEvent';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();

        $app->getEventManager()->getSharedManager()->attach('*',
            self::EVENT_CREATE_USER_EVENT,
            function (EventInterface $e) use ($sm) {
                $params = $e->getParams();
                $eventService = $sm->get(EventService::class);
                $eventService->create(
                    $params['type'],
                    $params['userId'],
                    $params['comment'],
                    $params['creatingUserId'] ?? null,
                    $params['commissionId'] ?? null
                );
            });

        $eventManager = $app->getEventManager();

        // attach listener only for browser request
        if (get_class($e->getRequest()) == Request::class) {
            $sharedEventManager = $eventManager->getSharedManager();
            $sharedEventManager->attach(DispatchableInterface::class, 'dispatch', [$this, 'onDispatch'], 100);
            // displatch is called TWICE - once for Laminas\Mvc\Application and once for a Controller
            // -> hence the identified Dispatchable (which means controller)
        }
    }

    public function onDispatch(MvcEvent $e)
    {
        $serviceLocator = $e->getApplication()->getServiceManager();
        $authenticationService = $serviceLocator->get(AuthenticationService::class);
        $objectManager = $serviceLocator->get(ObjectManager::class);

        // check if user has temporary password
        if ($authenticationService->hasIdentity()) {
            $user = $objectManager->find(User::class, $authenticationService->getIdentity()['id']);
            if ($user->getTempPassword() && $e->getRequest()->getUri()->getPath() != '/users/change-password/current') {
                $fm = $serviceLocator->get('ControllerPluginManager')->get('FlashMessenger');
                $fm->addErrorMessage('Twoje hasło ma status tymczasowy, prosimy o jego zmianę.');
                $this->redirect($e->getApplication()->getResponse(), '/users/change-password/current');
            }
        }
    }

    /**
     * Redirects to given URL.
     *
     * @param Response $response
     * @param string   $url
     */
    private function redirect(Response $response, string $url)
    {
        $response->setStatusCode(302);
        $response->getHeaders()
            ->addHeaderLine('Location', $url);
        $response->sendHeaders();
    }
}