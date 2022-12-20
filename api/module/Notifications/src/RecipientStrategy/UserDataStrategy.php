<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.04.18
 * Time: 10:46
 */

namespace Notifications\RecipientStrategy;


use Doctrine\Persistence\ObjectManager;
use Users\Entity\User;
use Users\Entity\UserData;
use Users\Entity\UserDataDetail;

class UserDataStrategy implements StrategyInterface
{
    private $objectManager;
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function get(string $staticParams = null, array $runtimeParams = [])
    {
        /** @var User $user */
        $user = $runtimeParams['user'];
        $detailEmail = $this->objectManager->getRepository(UserDataDetail::class)
            ->getActiveValueForKey($user->getId(), UserData::KEY_USER_EMAIL);

        $validator = new \Laminas\Validator\EmailAddress();
        $data = ($validator->isValid($detailEmail)) ? $detailEmail : $user->getEmail();
        if ($data && $validator->isValid($data)) {
            return $data;
        }
        return '';
    }
}