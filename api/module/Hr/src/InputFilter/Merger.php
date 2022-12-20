<?php

namespace Hr\InputFilter;

use Laminas\InputFilter\Factory;
use Laminas\InputFilter\InputFilterInterface;

class Merger
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function merge(string $requestedName, array $inputFilter): InputFilterInterface
    {
        $inputFilterSpecs = $this->config['input_filter_specs'][$requestedName] ?? [];

        foreach ($inputFilterSpecs as &$spec) {
            if (isset($inputFilter[$spec['name']])) {
                $field = $inputFilter[$spec['name']];

                $spec['required'] = $field['required'];

                if (isset($field['validators'])) {
                    $spec['validators'] = $field['validators'];
                }
                if (isset($field['filters'])) {
                    $spec['filters'] = $field['filters'];
                }
            }
        }

        $factory = new Factory();

        return $factory->createInputFilter($inputFilterSpecs);
    }
}