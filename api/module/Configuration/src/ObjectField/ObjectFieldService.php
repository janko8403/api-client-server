<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 12.07.17
 * Time: 12:24
 */

namespace Configuration\ObjectField;


use Configuration\Entity\ObjectField;
use Doctrine\Persistence\ObjectManager;

class ObjectFieldService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * ObjectFieldService constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(
        ObjectManager $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param $data
     */
    public function saveSequence($data){

        foreach ($data['sequence'] as $i => $sequence){
            /**
             * @var \Configuration\Entity\ObjectField
             */
            $objectField = $this->objectManager->getRepository(ObjectField::class)->find($sequence);

            $objectField->setSequence($i+1);
            $objectField->setLabel($data['label'][$sequence]);
            $this->objectManager->persist($objectField);
        }

        $this->objectManager->flush();
    }
}