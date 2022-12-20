<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 16.04.18
 * Time: 13:49
 */

namespace Users\Form;


use Users\Entity\User;
use Laminas\Filter;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;

class ForgottenPasswordForm extends Form implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct($objectManager)
    {
        parent::__construct('forgottenPassword', []);
        $this->objectManager = $objectManager;
        $this->setAttribute('class', 'login-form');
        $this->add([
            'type' => 'text',
            'name' => 'login',
            'attributes' => [
                'placeholder' => 'Login',
            ],
        ]);

        $this->add([
            'type' => 'radio',
            'name' => 'messageType',
            'options' => [
                'label' => 'Jak chcesz otrzymać kod?',
                'value_options' => [
                    'sms' => 'SMS',
                    'email' => 'E-mail',
                ],
                'btn-group' => ['btn-class' => 'btn tikrow-radio'],
            ],
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
    }

    public function getInputFilterSpecification()
    {
        return [
            'login' => [
                'required' => 'true',
                'validators' => [
                    new \DoctrineModule\Validator\ObjectExists([
                        'object_manager' => $this->objectManager,
                        // object repository to lookup
                        'object_repository' => $this->objectManager->getRepository(User::class),

                        // fields to match
                        'fields' => ['login', 'isactive'],
                        'use_context' => true,
                        'message' => 'Brak loginu w systemie'
                    ]),
                ],
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\StripTags::class],
                ],
            ],
            'messageType' => [
                'required' => true,
            ],
        ];
    }
}