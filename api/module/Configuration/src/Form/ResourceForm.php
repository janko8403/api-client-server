<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 28.06.17
 * Time: 17:17
 */

namespace Configuration\Form;


use Configuration\Fieldset\ResourceFieldset;
use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Form;

class ResourceForm extends Form
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager){
        parent::__construct('resourceForm');

        $this->objectManager = $objectManager;

        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'editForm');
        $resourceFieldset = new ResourceFieldset($objectManager);
        $resourceFieldset->setUseAsBaseFieldset(true);
        $this->add($resourceFieldset);

        $this->add([
            'type' => 'submit',
            'name' => 'save',
            'attributes' => [
                'class' => 'btn-sl',
                'value' => 'Zapisz'
            ],
        ]);
    }
}