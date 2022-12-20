<?php


namespace Notifications\Sms;


use Notifications\SmsCallback\CallbackFactory;

class CallbackService
{
    /**
     * @var SmsService
     */
    private $smsService;

    /**
     * @var CallbackFactory
     */
    private $factory;

    /**
     * CallbackService constructor.
     * @param SmsService $smsService
     * @param CallbackFactory $factory
     */
    public function __construct(SmsService $smsService, CallbackFactory $factory)
    {
        $this->smsService = $smsService;
        $this->factory = $factory;
    }

    public function process(string $msgId, string $smsFrom, string $smsText): void
    {
        $log = $this->smsService->logReport($msgId, 'RESPONSE_RECIEVED', $smsFrom);

        if ($log && $cu = $log->getCommissionUser()) {
            $this->factory->factory($log->getType())->process($cu, $smsText);
        }
    }

    public function logReport(string $msgId, string $statusName, string $from): void
    {
        $this->smsService->logReport($msgId, $statusName, $from);
    }
}