<?php
namespace Application\Form;

use Laminas\Form\Form;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct('loginForm');

        $this->add([
            'type' => 'text',
            'name' => 'login',
            'options' => [

            ],
            'attributes' => [
                'placeholder' => 'Numer telefonu',
            ],
        ]);
        $this->add([
            'type' => 'password',
            'name' => 'password',
            'options' => [
            ],
            'attributes' => [
                'placeholder' => 'Hasło',
            ],
        ]);
        $this->add([
            'type' => 'submit',
            'name' => 'send',
            'attributes' => [
                'class' => 'login-button',
                'value' => 'ZALOGUJ',
            ],
        ]);
    }
}

