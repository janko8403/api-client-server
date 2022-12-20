<?php

namespace Application;

use Application\Authentication\AuthenticationPostListener;
use Application\Authorization\AuthorizationListener;
use Hr\Acl\AclService;
use Hr\Authentication\AuthenticationService;
use Laminas\ApiTools\MvcAuth\Authentication\DefaultAuthenticationListener;
use Laminas\ApiTools\MvcAuth\Authentication\OAuth2Adapter;
use Laminas\ApiTools\MvcAuth\MvcAuthEvent;
use Laminas\Cache\StorageFactory;
use Laminas\EventManager\EventInterface;
use Laminas\Http\Response;
use Laminas\I18n\Translator\Loader\PhpArray;
use Laminas\I18n\Translator\Resources;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use Rollbar\Payload\Level;
use Rollbar\Rollbar;

class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    const PATH_WITHOUT_AUTH = [
//        '/',
        '/admin',
        '/login',
        '/login-client',
        '/registration',
        '/commissions/confirmation',
        '/reset-password',
        '/get-code',
        '/sms-callback/report',
        '/sms-callback/response',
        '/partner',
        '/no-access',
    ];

    public function onBootstrap(EventInterface $e)
    {
        $this->initRollbar($e);
        $this->processAuthentication($e);
        $this->processAuthorization($e);
        $this->setCustomVariables($e);
    }

    private function initRollbar($e)
    {
        $application = $e->getApplication();
        $config = $application->getConfig();

        if (!empty($config['rollbar']['access_token'])) {
            Rollbar::init($config['rollbar']);

            $eventManager = $application->getEventManager();
            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onError']);
            $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, [$this, 'onError']);
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param EventInterface $e
     */
    private function processAuthentication(EventInterface $e)
    {
        if (empty($e->getApplication()->getRequest()->getUri()->getHost())) {
            // console request, return
            return;
        }
        /** @var ServiceManager $serviceManager */
        $serviceManager = $e->getApplication()->getServiceManager();

        $authenticationService = $serviceManager->get(AuthenticationService::class);

        $app = $e->getApplication();
        $events = $app->getEventManager();
        $events->attach(
            MvcAuthEvent::EVENT_AUTHENTICATION,
            function ($e) use ($serviceManager) {
                $listener = $serviceManager->get(DefaultAuthenticationListener::class);
                $adapter = $serviceManager->get(OAuth2Adapter::class);
                $listener->attach($adapter);
            },
            1000
        );

        // Attach an event to replace the Identity with a DoctrineAuthenticatedIdentity
        $authenticationPostListener = $serviceManager->get(AuthenticationPostListener::class);
        $events->attach(
            MvcAuthEvent::EVENT_AUTHENTICATION_POST,
            $authenticationPostListener,
            100
        );

        $uri = $e->getRequest()->getUri()->getPath();
        if ($this->isApiUrl($uri)) {
            $events->attach(
                MvcAuthEvent::EVENT_AUTHORIZATION,
                $serviceManager->get(AuthorizationListener::class),
                100
            );

            return;
        }

        if (
            !$authenticationService->hasIdentity()
            && !in_array($uri, self::PATH_WITHOUT_AUTH)
        ) {
            if ($e->getApplication()->getRequest()->isXmlHttpRequest()) {
                $response = $e->getApplication()->getResponse();
                $response->setStatusCode(403);
                $response->setContent('Session expired');
                $response->sendHeaders();
                exit();
            } else {
                $requestUri = $e->getApplication()->getRequest()->getRequestUri();
                $this->redirect($e->getApplication()->getResponse(), "/login?return=" . $requestUri);
            }
        }
    }

    /**
     * @param $uri
     * @return false|int
     */
    private function isApiUrl($uri)
    {
        return preg_match("/^\/(apigility|oauth|api).*/", $uri);
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
        exit();
    }

    private function processAuthorization(EventInterface $e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('*', MvcEvent::EVENT_DISPATCH, function (MvcEvent $e) {
            $uri = $e->getRequest()->getUri()->getPath();
            if ($this->isApiUrl($uri)) {
                return true;
            }

            $routerWithoutAuth = [
                'home',
                'admin',
                'login',
                'login-client',
                'logout',
                'registration',
                'commissions/confirmation',
                'reset-password',
                'get-code',
                'sms-callback',
                'partner',
                'no-access',
            ];
            if (
                in_array($e->getRouteMatch()->getMatchedRouteName(), $routerWithoutAuth)
            ) {
                return true;
            }

            $aclService = $e->getApplication()->getServiceManager()->get(AclService::class);
            $authenticationService = $e->getApplication()->getServiceManager()->get(AuthenticationService::class);

            $resourceParts = $aclService->getResourceParts($e->getRouteMatch());
            $configurationPositionId = $authenticationService->getIdentity()['configurationPositionId'] ?? 0;
            if ($aclService->getAcl()->hasRole($configurationPositionId)) {
                $hasAccess = $aclService->hasAccess($configurationPositionId, $resourceParts);
            } else {
                $hasAccess = false;
            }

            if (!$hasAccess) {
                if ($e->getApplication()->getRequest()->isXmlHttpRequest()) {
                    $response = $e->getApplication()->getResponse();
                    $response->setStatusCode(401);
                    $response->setContent('Unauthorized');
                    $response->sendHeaders();
                    exit();
                } else {
                    $this->redirect($e->getApplication()->getResponse(), '/');
                }
            }
        }, 1000);
    }

    private function setCustomVariables($e)
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $e->getApplication()->getServiceManager();
        $customCss = $serviceManager->get('config')['view_manager']['css'] ?? [];
        $customForm = $serviceManager->get('config')['login_form'] ?? [];
        $customTittle = $serviceManager->get('config')['view_manager']['title'] ?? [];
        $customFavico = $serviceManager->get('config')['view_manager']['favicon'] ?? [];
        $customLoginIcon = $serviceManager->get('config')['view_manager']['custom_login_image'] ?? [];
        $loginImgHref = $serviceManager->get('config')['view_manager']['login_img_href'] ?? [];
        $logo = $serviceManager->get('config')['view_manager']['logo'] ?? [];
        $logoSmall = $serviceManager->get('config')['view_manager']['logo_small'] ?? [];
        $customScriptHead = $serviceManager->get('config')['view_manager']['custom_script_head'] ?? null;

        if (!empty($customCss)) {
            $e->getViewModel()->setVariable('custom_css', $customCss);
        }
        if (!empty($customForm)) {
            $e->getViewModel()->setVariable('custom_form', $customForm);
        }
        if (!empty($customFavico)) {
            $e->getViewModel()->setVariable('custom_favico', $customFavico);
        }
        if (!empty($customTittle)) {
            $e->getViewModel()->setVariable('custom_title', $customTittle);
        }
        if (!empty($customLoginIcon)) {
            $e->getViewModel()->setVariable('custom_login_icon', $customLoginIcon);
        }
        if (!empty($loginImgHref)) {
            $e->getViewModel()->setVariable('custom_login_href', $loginImgHref);
        }
        if (!empty($logo)) {
            $e->getViewModel()->setVariable('settings_logo', $logo);
        } else {
            $e->getViewModel()->setVariable('settings_logo', 'logo.png');
        }
        if (!empty($logoSmall)) {
            $e->getViewModel()->setVariable('settings_logo_small', $logoSmall);
        } else {
            $e->getViewModel()->setVariable('settings_logo_small', 'pizza.png');
        }
        if (!empty($customScriptHead)) {
            $e->getViewModel()->setVariable('custom_script_head', $customScriptHead);
        }
    }

    public function onError(MvcEvent $event)
    {
        $exception = $event->getParam('exception');
        if ($exception) {
            Rollbar::logger()->log(Level::ERROR, $exception, [], true);
        }
    }

    /**
     * @param EventInterface $e
     * @throws \Exception
     */
    private function setupTranslations(EventInterface $e)
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $e->getApplication()->getServiceManager();

        $key = 'pl_PL';
        \Locale::setDefault($key);
        $translator = $serviceManager->get('MvcTranslator');
        $translator->setLocale($key);

        // validation messages
        $parts = [rtrim(Resources::getBasePath(), '/'), 'pl', 'Laminas_Validate.php'];
        $translator->addTranslationFile(
            PhpArray::class,
            implode(DIRECTORY_SEPARATOR, $parts),
            'default',
            $key
        );

        // translations cache
        $cacheAdapter = $serviceManager->get('Laminas\Cache\Storage\Adapter\AbstractAdapter');
        $translator->setCache($cacheAdapter);
        $translator->setCache(StorageFactory::factory([
            'adapter' => [
                'name' => 'Filesystem',
                'options' => [
                    'cache_dir' => getcwd() . '/data/cache',
                    'ttl' => '3600',
                ],
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [],
                ],
                'exception_handler' => [
                    'throw_exceptions' => true,
                ],
            ],
        ]));

        $e->getViewModel()->locale = 'pl';
    }
}
