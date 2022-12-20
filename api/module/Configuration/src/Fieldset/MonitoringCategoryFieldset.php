<?php
namespace Configuration\Fieldset;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Monitorings\Entity\MonitoringCategory;
use Laminas\Filter;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;

class MonitoringCategoryFieldset extends Fieldset implements InputFilterProviderInterface
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('monitoringCategory');

        $this->objectManager = $objectManager;
        $this->setHydrator(new DoctrineHydrator($objectManager,MonitoringCategory::class))->setObject(new MonitoringCategory());

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Nazwa',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'sequence',
            'options' => [
                'label' => 'Kolejność',
            ],
        ]);
    }

    public function getInputFilterSpecification(){
        return [
            'name' => [
                'required' => true,
                'filters'  => [
                    ['name' => Filter\StringTrim::class],
                    ['name' => Filter\StripTags::class],
                ],
            ],
            'sequence' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Validator\Digits::class,
                    ],
                ],
            ],
        ];
    }
}
