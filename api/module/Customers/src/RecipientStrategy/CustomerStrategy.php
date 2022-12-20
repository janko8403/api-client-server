<?php

namespace Customers\RecipientStrategy;

use Customers\Entity\Customer;
use Notifications\RecipientStrategy\StrategyInterface;
use Laminas\Validator\EmailAddress;

class CustomerStrategy implements StrategyInterface
{
    public function get(string $staticParams = null, array $runtimeParams = [])
    {
        /** @var Customer $customer */
        $customer = $runtimeParams['customer'];
        $data = trim($customer->getEmail());
        $validator = new EmailAddress();
        if ($data && $validator->isValid($data)) {
            return $data;
        }

        return '';
    }
}