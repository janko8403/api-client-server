<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 20.10.17
 * Time: 15:36
 */

namespace Users\Fieldset;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Hr\Fieldset\BaseFieldset;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Validator\Callback;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;
use Users\Entity\User;

class ChangePasswordFieldset extends BaseFieldset
{
    public function __construct(ObjectManager $objectManager, string $name)
    {
        parent::__construct($name);

        $this->setHydrator(new DoctrineHydrator($objectManager, User::class))->setObject(new User());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'orgPassword',
            'options' => [
                'label' => 'Obecne hasło',
            ],
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Nowe hasło',
            ],
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password2',
            'options' => [
                'label' => 'Potwierdź nowe hasło',
            ],
        ]);

        $defaultFilter = [
            ['name' => StringTrim::class],
            ['name' => StripTags::class],
        ];

        $form = $this;

        $this->inputFilter = [
            'password' => [
                'required' => true,
                'filters' => $defaultFilter,
                'validators' => [
                    [
                        'name' => 'Identical',
                        'options' => [
                            'token' => 'password2',
                        ],
                    ],
                    new Regex([
//                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/',
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d$@$!%*#?&\(\)\[\]\{\}\<\>\^_\-\=\+]{6,}$/',
                        'message' => 'Hasło musi zawierać małe, wielkie litery oraz cyfrę.',
                    ]),
                    new StringLength(['min' => 8]),
                    new Callback([
                        'callback' => function ($value) use ($form) {
                            $currentPassword = $form->getObject()->getPassword();

                            return $currentPassword != sha1($value);
                        },
                        'message' => 'Nowe hasło musi się różnić od obecnego',
                    ]),
                ],
            ],
            'orgPassword' => [
                'required' => true,
                'filters' => $defaultFilter,
                'validators' => [
                    new Callback([
                        'callback' => function ($value) use ($form) {
                            $currentPassword = $form->getObject()->getPassword();

                            return (new Bcrypt())->verify($value, $currentPassword);
                        },
                        'message' => 'Podano nieprawidłowe hasło',
                    ]),
                ],
            ],
        ];
    }

}