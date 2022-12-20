<?php
/**
 * Created by PhpStorm.
 * User: pawelz
 * Date: 2019-03-06
 * Time: 15:24
 */

namespace Notifications\Sms;

use Doctrine\Persistence\ObjectManager;
use Notifications\Entity\Notification;
use Notifications\Entity\SmsLog;
use Smsapi\Client\Curl\SmsapiHttpClient;
use Smsapi\Client\Feature\Sms\Bag\SendSmsBag;

class SmsService
{
    /**
     * SmsService constructor.
     *
     * @param ObjectManager $objectManager
     * @param string        $token
     * @param array         $catchAll
     */
    public function __construct(private ObjectManager $objectManager, private string $token, private array $catchAll)
    {
    }

    /**
     * Sends SMS message.
     *
     * @param string $phonenumber
     * @param string $message
     * @param string $sender
     */
    public function send(string $phonenumber, string $message, string $sender = 'Tikrow'): void
    {
        $phonenumbers = $this->catchAll ?: (array)$phonenumber;

        foreach ($phonenumbers as $phonenumber) {
            $bag = SendSmsBag::withMessage($phonenumber, $message);
            $bag->normalize = true;
            $bag->from = $sender;

            $log = new SmsLog();
            $log->setNumber($phonenumber);

            try {
                $sms = (new SmsapiHttpClient())
                    ->smsapiPlService($this->token)
                    ->smsFeature()
                    ->sendSms($bag);
                $log->setApiId($sms->id);
                $log->setStatus($sms->status);
            } catch (\Exception $e) {
                $log->setError($e->getMessage());
            }

            $this->objectManager->persist($log);
        }

        $this->objectManager->flush();
    }

    /**
     * Logs delivery reports for given message.
     *
     * @param string $msgId
     * @param string $statusName
     * @param string $from
     * @return SmsLog|null
     */
    public function logReport(string $msgId, string $statusName, string $from)
    {
        $log = $this->objectManager->getRepository(SmsLog::class)->findOneBy(['apiId' => $msgId]);

        if ($log) {
            $log->setStatus($statusName);
            $log->setSender($from);
            $this->objectManager->persist($log);
            $this->objectManager->flush();

            return $log;
        }
    }

    /**
     * Fetches sms notification content.
     *
     * @param int $type
     * @param int $instance
     * @return string
     * @throws \Exception
     */
    public function getContent(int $type, int $instance): string
    {
        $notification = $this->objectManager->getRepository(Notification::class)
            ->findOneBy(['type' => $type, 'instance' => $instance, 'transport' => Notification::TRANSPORT_SMS]);

        if ($notification) {
            return $notification->getContent();
        }

        throw new \Exception("Unknown SMS notification of type: `$type`");
    }
}