<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.04.18
 * Time: 10:50
 */

namespace Users\Notification;


use Hr\Config\ConfigAwareInterface;
use Hr\Config\ConfigAwareTrait;
use Notifications\Entity\Notification;
use Notifications\Notification\NotificationInterface;
use Notifications\Notification\NotificationTrait;

class PasswordResetNotification implements NotificationInterface, ConfigAwareInterface
{
    use NotificationTrait, ConfigAwareTrait;

    public function send(array $params)
    {
        if (empty($params['user'])) {
            throw new \Exception("Cannot send notification without user data.");
        }

        // find notification
        /** @var Notification $notification */
        $notification = $this->objectManager->getRepository(Notification::class)
            ->findOneBy(['type' => Notification::TYPE_EMAIL_RESET_PASSWORD]);

        // get recipient strategies
        $recipients = [];
        foreach ($notification->getRecipients() as $recipient) {
            $recipientStrategy = $this->recipientFactory->factory($recipient->getStrategy());
            $recipients[] = $recipientStrategy->get($recipient->getParams(), $params);
        }

        if (empty($recipients)) {
            return false;
        }

        $subject = $notification->getSubject();
        $url = sprintf(
            "%s/change-password?token=%s",
            $this->config['frontend_url'],
            $params['user']->getPasswordToken()
        );
        $content = $this->replace(
            '[link]',
            sprintf("<a href='%s'>%s</a>", $url, $url),
            $notification->getContent()
        );
        $transport = $this->transportFactory->factory($notification->getTransport());
        $transport->send($subject, $content, $recipients);
    }

    private function replace($from, $to, $subject)
    {
        return str_replace($from, $to, $subject);
    }
}