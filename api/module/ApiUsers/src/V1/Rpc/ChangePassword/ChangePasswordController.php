<?php

namespace ApiUsers\V1\Rpc\ChangePassword;

use ApiUsers\V1\Service\PasswordService;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\ApiProblem\ApiProblemResponse;
use Laminas\Http\Response as HttpResponse;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * @method InputFilterInterface|null getInputFilter() \Laminas\ApiTools\ContentValidation\InputFilter\InputFilterPlugin->__invoke()
 */
class ChangePasswordController extends AbstractActionController
{
    public function __construct(private PasswordService $service)
    {
    }

    public function changePasswordAction(): array|ApiProblemResponse
    {
        $data = $this->getInputFilter()->getValues();
        $token = trim($this->params()->fromQuery('token'));

        if ($token) {
            $user = $this->service->findUserByToken($token);
            if ($user) {
                $this->service->setNewPassword($user, $data['password']);
                return [];
            }
        }

        return new ApiProblemResponse(new ApiProblem(HttpResponse::STATUS_CODE_400, 'Niepoprawny token'));
    }
}
