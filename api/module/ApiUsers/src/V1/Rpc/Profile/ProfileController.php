<?php

namespace ApiUsers\V1\Rpc\Profile;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Users\Entity\User;

class ProfileController extends AbstractActionController
{
    public function __construct(
        private ProfileService $profileService,
    )
    {
    }

    public function profileAction(): ViewModel
    {
        /** @var User $user */
        $user = $this->apiIdentity();
        return new JsonModel($this->profileService->getProfile($user));
    }
}