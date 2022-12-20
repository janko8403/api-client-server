<?php

namespace Hr\Controller;

use Users\Entity\Activity;
use Users\Service\UserService;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Controller responsible for handling user activity logging.
 *
 * Dependencies:
 * - Users\Service\UserService
 *
 * @package Hr\Controller
 */
class ActivityLogController extends AbstractActionController
{
    /**
     * @var UserService
     */
    private $service;

    /**
     * ActivityLogController constructor.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function linkAction()
    {
        return $this->doLog(Activity::TYPE_LINK);
    }

    public function pageLoadAction()
    {
        return $this->doLog(Activity::TYPE_PAGE_LOAD);
    }

    public function documentsUserAction()
    {
        return $this->doLog(Activity::TYPE_DOCUMENTS_USER);
    }

    public function logAction()
    {
        return $this->doLog($this->params('type'));
    }

    private function doLog(int $type)
    {
        $server = $this->getRequest()->getServer();
        $details = $this->params()->fromPost('details');

        if (!empty($details)) {
            $this->service->logActivity(
                $this->identity()['id'],
                $type,
                $server->REMOTE_ADDR,
                $server->HTTP_USER_AGENT,
                $details
            );
        }

        return $this->getResponse();
    }
}