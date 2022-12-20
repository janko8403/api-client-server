<?php


namespace Notifications\SmsCallback;


use Commissions\Entity\CommissionUser;

interface CallbackInterface
{
    /**
     * Processes sms text.
     *
     * @param CommissionUser $commissionUser
     * @param string $text
     */
    public function process(CommissionUser $commissionUser, string $text): void;
}