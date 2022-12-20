<?php


namespace Users\Controller;


use Hr\Controller\BaseController;
use Users\Service\LoginService;
use Laminas\Json\Json;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;

class LoginController extends BaseController
{
    /**
     * @var LoginService
     */
    private $service;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * LoginController constructor.
     *
     * @param LoginService $service
     * @param PhpRenderer  $renderer
     */
    public function __construct(LoginService $service, PhpRenderer $renderer)
    {
        $this->service = $service;
        $this->renderer = $renderer;
        $this->addBreadcrumbsPart('Użytkownicy');
    }

    public function indexAction()
    {
        $this->addBreadcrumbsPart('Logowanie');

        $vm = new ViewModel([
            'waiting' => $this->service->getAllRequests(),
        ]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function createAction()
    {
        if ($this->getRequest()->isPost()) {
            $partnerId = $this->params('id');
            $userId = $this->identity()['id'];
            $reason = $this->params()->fromPost('reason');
            $loginId = $this->service->createRequest($partnerId, $userId, $reason);

            return new JsonModel([
                'refresh_url' => $this->url()->fromRoute('users/login', ['action' => 'refresh', 'id' => $loginId]),
            ]);
        }

        return $this->getResponse();
    }

    public function confirmAction()
    {
        $id = $this->params('id');
        $response = $this->getResponse();

        if ($this->getRequest()->isPost()) {
            $this->service->confirmRequest($id, $this->identity()['id']);
            $response->setContent('ok');

        }

        return $response;
    }

    public function refreshAction()
    {
        $id = $this->params('id');
        $login = $this->service->getRequest($id);
        $confirmed = (bool)$login->getConfirmationDate();

        $vm = new ViewModel(['confirmed' => $confirmed]);
        $vm->setTerminal(true);
        $vm->setTemplate('users/login/refresh');

        return new JsonModel([
            'confirmed' => $confirmed,
            'html' => $this->renderer->render($vm),
        ]);
    }

    public function loginAction()
    {
        $id = $this->params('id');
        $request = $this->service->getRequest($id);

        // update remote login date
        $this->service->markLoginDate($id);

        // generate oauth token
        $tokens = $this->service->generateTokens($request->getPartner()->getLogin());

        // get redirect url
        $redirectUrl = $this->service->getRedirectUrl($request->getPartner()->getId());

        // set cookie
        $cookie = $this->service->getCookie($tokens, $request->getPartner()->getId());

        $response = $this->getResponse();
        $response->getHeaders()->addHeader($cookie);
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $response->setContent(Json::encode(['redirect_url' => $redirectUrl]));

        return $response;
    }
}