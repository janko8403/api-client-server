<?php


namespace Notifications\Sms;


use Commissions\Entity\CommissionUser;
use Doctrine\Persistence\ObjectManager;
use Notifications\Entity\SmsHistory;
use SMSApi\Api\Response\MessageResponse;

class HistoryService
{
    const EVENT_SEND = 'smsHistorySendEvent';
    const EVENT_RESPONSE = 'smsHistoryResponseEvent';

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * HistoryService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Add send SMS log entry.
     *
     * @param string              $text
     * @param MessageResponse     $response
     * @param CommissionUser|null $cu
     */
    public function addSendLog(string $text, MessageResponse $response, ?CommissionUser $cu): void
    {
        $history = new SmsHistory();
        $history->setApiId($response->getId());
        $history->setNumber($response->getNumber());
        $history->setParts($response->getPoints());
        $history->setSendDate(new \DateTime());
        $history->setText($text);

        if ($cu) {
            $history->setName($cu->getUser()->getFullName());
            $history->setAdditionalInfo([
                'cuid' => $cu->getId(),
            ]);
        }

        $this->objectManager->persist($history);
        $this->objectManager->flush();
    }

    /**
     * Adds SMS response information.
     *
     * @param string $msgId
     * @param string $text
     */
    public function addResponseLog(string $msgId, string $text): void
    {
        $messages = $this->objectManager->getRepository(SmsHistory::class)->findBy(['apiId' => $msgId]);

        foreach ($messages as $message) {
            $message->setResponseDate(new \DateTime());
            $message->setResponseText($text);
            $this->objectManager->persist($message);
        }

        $this->objectManager->flush();
    }
}