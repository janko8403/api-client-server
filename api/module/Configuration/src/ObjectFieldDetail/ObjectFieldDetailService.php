<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 10.07.17
 * Time: 18:45
 */

namespace Configuration\ObjectFieldDetail;


use Configuration\Entity\ObjectField;
use Configuration\Entity\ObjectFieldDetail;
use Configuration\Entity\Position;
use Doctrine\Persistence\ObjectManager;

class ObjectFieldDetailService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * ObjectFieldDetailService constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(
        ObjectManager $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Returns fieldDetails in array
     * @param $fields
     * @return array
     */

    public function getObjectFieldDetails($fields) : array {
        $data = [];

        foreach ($fields as $field) {
            foreach ($field->getDetails() as $details) {
                $data[$field->getId()][$details->getPosition()->getId()]['small'] = $details->getSettingSmall();
                $data[$field->getId()][$details->getPosition()->getId()]['medium'] = $details->getSettingMedium();
                $data[$field->getId()][$details->getPosition()->getId()]['large'] = $details->getSettingLarge();
            }
        }

        return $data;
    }

    /**
     *
     * @param $data
     */
    public function saveObjectFieldVisibility($data) {
        foreach ($data['answers'] as $field => $values){
            foreach($values as $position => $value){

                /**
                 * @var ObjectFieldDetail
                 */
                $detail = $this->objectManager->getRepository(ObjectFieldDetail::class)->findOneBy(['field' => $field, 'position' => $position]);
                if (empty($detail)){
                    $detail = new ObjectFieldDetail();
                    $detailPosition = $this->objectManager->getRepository(Position::class)->find($position);
                    $detailField = $this->objectManager->getRepository(ObjectField::class)->find($field);
                    $detail->setPosition($detailPosition);
                    $detail->setField($detailField);
                }
                $detail->setSettingSmall($value['small']);
                $detail->setSettingMedium($value['medium']);
                $detail->setSettingLarge($value['large']);
                $this->objectManager->persist($detail);
            }
        }
        $this->objectManager->flush();
    }
}