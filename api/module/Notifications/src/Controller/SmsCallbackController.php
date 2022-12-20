<?php
/**
 * Created by PhpStorm.
 * User: pawelz
 * Date: 2019-03-06
 * Time: 17:42
 */

namespace Notifications\Controller;

use Notifications\Sms\CallbackService;
use Notifications\Sms\HistoryService;
use Hr\Controller\BaseController;

class SmsCallbackController extends BaseController
{
    /**
     * @var CallbackService
     */
    private $callbackService;

    /**
     * SmsCallbackController constructor.
     *
     * @param CallbackService $callbackService
     */
    public function __construct(CallbackService $callbackService)
    {
        $this->callbackService = $callbackService;
    }

    public function reportAction()
    {
        $query = $this->params()->fromQuery();
        $this->callbackService->logReport($query['MsgId'], $query['status_name'], $query['from']);

        return $this->getResponse()->setContent('OK');
    }

    public function responseAction()
    {
        if ($this->getRequest()->isPost()) {
            $post = $this->params()->fromPost();

            if (!empty($post['MsgId'])) {
                $this->callbackService->process($post['MsgId'], $post['sms_from'], $post['sms_text']);
                $this->getEventManager()->trigger(HistoryService::EVENT_RESPONSE, null, [
                    'MsgId' => $post['MsgId'], 'text' => $post['sms_text'],
                ]);
            }
        }

        return $this->getResponse()->setContent('OK');
    }
}