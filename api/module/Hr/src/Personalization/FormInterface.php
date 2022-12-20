<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 26.10.2016
 * Time: 18:36
 */

namespace Hr\Personalization;

use Laminas\Form\FieldsetInterface;

interface FormInterface
{
    public function __construct(FieldsetInterface $form);

    public function hasField(string $field);

    public function displayField(string $field);
}