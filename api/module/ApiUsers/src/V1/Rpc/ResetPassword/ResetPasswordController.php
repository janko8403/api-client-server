<?php

namespace ApiUsers\V1\Rpc\ResetPassword;

use ApiUsers\V1\Service\PasswordService;
use Laminas\Mvc\Controller\AbstractActionController;
use Notifications\Entity\Notification;
use Users\Notification\PasswordResetNotification;

class ResetPasswordController extends AbstractActionController
{

    public function __construct(private PasswordService $service)
    {
    }

    public function resetPasswordAction()
    {
        $email = $this->getInputFilter()->getValue('email');
        $user = $this->service->fetchUserByEmail($email);

        if ($user) {
            $this->service->updatePasswordToken($user);
            $this->getEventManager()->trigger(
                Notification::EVENT_SEND_NOTIFICATION,
                PasswordResetNotification::class,
                ['user' => $user]
            );
        }

        return [];
    }
}
