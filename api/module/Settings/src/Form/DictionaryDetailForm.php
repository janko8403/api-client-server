<?php

namespace Settings\Form;

use Doctrine\Laminas\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Element\Collection;
use Laminas\Form\Form;
use Settings\Fieldset\DictionaryDetailFieldset;
use Settings\Fieldset\DictionaryDetailsDescriptionFieldset;
use Settings\Service\DictionaryDetailsDescriptionService;

class DictionaryDetailForm extends Form
{
    private $objectManager;

    /**
     * @var DictionaryDetailsDescriptionService
     */
    private $dictionaryDetailsDescriptionService;

    public function __construct(ObjectManager $objectManager, DictionaryDetailsDescriptionService $dictionaryDetailsDescriptionService)
    {
        parent::__construct('dictionaryDetail');

        $this->objectManager = $objectManager;
        $this->dictionaryDetailsDescriptionService = $dictionaryDetailsDescriptionService;

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($objectManager));


        $fieldset = new DictionaryDetailFieldset($objectManager);
        $fieldset->add([
            'type' => Collection::class,
            'name' => 'description',
            'options' => [
                'count' => 1,
                'should_create_template' => false,
                'allow_add' => true,
                'target_element' => new DictionaryDetailsDescriptionFieldset($objectManager),
            ],
        ]);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);
    }

    public function populateValues($data, $onlyBase = false): void
    {
        $values = $this->dictionaryDetailsDescriptionService->getDescriptionValues($data['dictionaryDetail']['id']);

        if (!empty($values)) {
            foreach ($values as $key => $value) {
                $this->get('dictionaryDetail')->get('description')->getTargetElement()->get($key)->setValue($value);
            }
        }
        parent::populateValues($data, $onlyBase);
    }

    public function setHydrationEntity(string $entityName)
    {
        $this->get('dictionaryDetail')
            ->setHydrator(new DoctrineHydrator($this->objectManager, $entityName))
            ->setObject(new $entityName());
    }
}