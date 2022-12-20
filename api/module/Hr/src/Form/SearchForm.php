<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 10.11.2016
 * Time: 16:06
 */

namespace Hr\Form;

use Laminas\InputFilter\InputFilterProviderInterface;

abstract class SearchForm extends BaseForm implements InputFilterProviderInterface
{
    const TYPE = 'search';

    public function __construct($name, array $options = [])
    {
        parent::__construct($name, $options);
        $this->setAttributes([
            'method' => 'GET',
            'class' => 'form-search',
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [];
    }

    public function setInputFilterSpecification($inputFilter)
    {

    }
}