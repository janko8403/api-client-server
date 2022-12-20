<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 18.04.18
 * Time: 09:33
 */

namespace Users\Form;


use Doctrine\Persistence\ObjectManager;
use Users\Entity\PasswordToken;
use Users\Service\RegistrationService;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\Callback;
use Laminas\Validator\Digits;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;

class ResetPasswordForm extends Form implements InputFilterProviderInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('resetPassword', []);
        $this->objectManager = $objectManager;

        $this->add([
            'type' => 'text',
            'name' => 'code',
            'options' => [
                'label' => 'Token',
            ]
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Nowe hasło'
            ]
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password2',
            'options' => [
                'label' => 'Potwierdź nowe hasło',
            ]
        ]);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ]
            ]
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz'
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        $defaultFilter = [
            ['name' => StringTrim::class],
            ['name' => StripTags::class]
        ];

        //$form = $this;
        $em = $this->objectManager;
        return [
            'code' => [
                'required' => true,
                'filters' => $defaultFilter,
                'validators' => [
                    new Digits(),
                    new Callback([
                        'callback' => function($value) use ($em) {
                        /** @var PasswordToken $passwordToken */
                            $passwordToken = $em->getRepository(PasswordToken::class)->findOneBy(['code' => $value]);
                            if (!$passwordToken || ($passwordToken && !$passwordToken->getIsactive())){
                                return false;
                            }

                            $lastToken = $em->getRepository(PasswordToken::class)->getLastCodeForUser($passwordToken->getUser()->getId());
                            if (!$lastToken) {
                                return false;
                            }
                            /** @var PasswordToken $token */
                            $token = $lastToken[0];

                            if ($token->getCode() != $value) {

                                $token->setNumberOfTries($token->getNumberOfTries() + 1);
                                if ($token->getNumberOfTries() >= RegistrationService::RESET_PASSWORD_ATTEMPTS) {
                                    $token->setIsactive(0);
                                }

                                $em->persist($token);
                                $em->flush();

                                return false;
                            }
                            
                            if ($token->getNumberOfTries() >= RegistrationService::RESET_PASSWORD_ATTEMPTS){
                                $token->setIsactive(0);

                                $em->persist($token);
                                $em->flush();

                                return false;
                            }

                            $token->setNumberOfTries($token->getNumberOfTries() + 1);
                            $em->persist($token);
                            $em->flush();

                            return true;
                        },
                        'message' => 'Podany token wygasł lub jest niepoprawny',
                    ]),
                ]
            ],
            'password' => [
                'required' => true,
                'filters' => $defaultFilter,
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password2',
                        ]
                    ],
                    new Regex([
//                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/',
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d$@$!%*#?&\(\)\[\]\{\}\<\>\^_\-\=\+]{6,}$/',
                        'message' => 'Hasło musi zawierać małe, wielkie litery oraz cyfrę.'
                    ]),
                    new StringLength(['min' => 8]),
                ]
            ],
        ];
    }
}