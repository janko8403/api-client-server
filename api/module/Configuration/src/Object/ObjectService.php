<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.06.2017
 * Time: 13:34
 */

namespace Configuration\Object;

use Configuration\Entity\ObjectGroup;
use Configuration\Entity\ObjectField;
use Configuration\Entity\ObjectFieldDetail;
use Configuration\Entity\Position;
use Configuration\Entity\Resource;
use Doctrine\Persistence\ObjectManager;

class ObjectService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \Mobile_Detect
     */
    private $mobileDetect;

    /**
     * @var Position
     */
    private $position;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * ObjectService constructor.
     * @param ObjectManager $objectManager
     * @param \Mobile_Detect $mobileDetect
     * @param Position $position
     * @param Resource $resource
     */
    public function __construct(
        ObjectManager $objectManager,
        \Mobile_Detect $mobileDetect,
        Position $position,
        Resource $resource
    ) {
        $this->objectManager = $objectManager;
        $this->mobileDetect = $mobileDetect;
        $this->position = $position;
        $this->resource = $resource;
    }

    /**
     * Gets configuration object and fills necessary fields and details.
     *
     * @param int $type Object type
     * @param string $name Object name
     * @param array $fields Array of fields to display
     * @param array $labels Array of fields' labels
     * @return ObjectGroup
     * @throws \Exception Thrown if fields count doesn't match labels count
     */
    public function getAndFillObjectDetails(int $type, string $name, array $fields, array $labels) : ObjectGroup
    {
        if (count($fields) != count($labels)) {
            throw new \Exception('Object fields count is diffrent than object field labels count.');
        }

        // check if object exists
        $object = $this->objectManager->getRepository(ObjectGroup::class)->getFieldOrder($name, $type);

        if (empty($object)) {
            $object = new ObjectGroup();
            $object->setType($type);
            $object->setName($name);
        }

        // check if object is connected to a resource
        if (!$object->getResources()->contains($this->resource)) {
            $object->addResource($this->resource);
        }

        $fieldCount = $object->getFields()->count();

        // check if all the fields are connected to a object
        foreach ($fields as $i => $field) {
            $objectField = $object->getFieldByName($field);

            if (empty($objectField)) {
                // add a field with details
                $objectField = new ObjectField();
                $objectField->setName($field);
                $objectField->setLabel($labels[$i]);
                $objectField->setSequence($fieldCount + $i + 1);
                $objectField->setObject($object);
                $object->addField($objectField);

                $this->addFieldDetail($objectField);
            } else {
                // check if every field has connected details for a position
                $detail = $this->objectManager->getRepository(ObjectFieldDetail::class)->findOneBy([
                    'field' => $objectField,
                    'position' => $this->position
                ]);

                if (empty($detail)) {
                    $this->addFieldDetail($objectField);
                }
            }
        }

        $this->objectManager->persist($object);
        $this->objectManager->flush();
        $this->objectManager->refresh($object);

        return $object;
    }

    /**
     * Gets fields to display on current device.
     *
     * @param array $fields
     * @return array
     */
    public function getDeviceFields(array $fields) : array
    {
        $displayFields = [];

        foreach ($fields as $name => $objectField) {
            /** @var ObjectFieldDetail $detail */
            $detail = $objectField->getDetailForPosition($this->position);

            if (
                ($detail->getSettingSmall() != ObjectFieldDetail::SETTING_NO && $this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet())
                || ($detail->getSettingMedium() != ObjectFieldDetail::SETTING_NO && $this->mobileDetect->isTablet())
                || ($detail->getSettingLarge() != ObjectFieldDetail::SETTING_NO && !$this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet())
            ) {
                $displayFields[$name] = [
                    'label' => $objectField->getLabel(),
                    'order' => $objectField->getSequence(),
                    'disabled' => false,
                    'display_add_dictionary' => false,
                    'disabled_if_not_empty' => false,
                ];

                // check wheather field should be disabled
                if ($this->checkDeviceConditions($detail, ObjectFieldDetail::SETTING_VIEW)) {
                    $displayFields[$name]['disabled'] = true;
                }

                // check wheather field should be disabled if not empty
                if (
                    $this->checkDeviceConditions($detail, ObjectFieldDetail::SETTING_SAVE_NOT_EMPTY)
                    || $this->checkDeviceConditions($detail, ObjectFieldDetail::SETTING_SAVE_ADD_NOT_EMPTY)
                ) {
                    $displayFields[$name]['disabled_if_not_empty'] = true;
                }

                // check wheather field should have add button (dictionary)
                if (
                    $this->checkDeviceConditions($detail, ObjectFieldDetail::SETTING_ADD)
                    || $this->checkDeviceConditions($detail, ObjectFieldDetail::SETTING_SAVE_ADD_NOT_EMPTY)
                ) {
                    $displayFields[$name]['display_add_dictionary'] = true;
                }
            }
        }

        return $displayFields;
    }

    /**
     * Generates object cache key string for given parameters and current position/device.
     *
     * @param int $type
     * @param string $name
     * @return string
     */
    public function generateObjectCacheKey(int $type, string $name) : string
    {
        return sprintf(
            'ObjectService_%s_t%d_p%d_m%d_t%d',
            $name,
            $type,
            $this->position->getId(),
            (int) $this->mobileDetect->isMobile(),
            (int) $this->mobileDetect->isTablet()
        );
    }
    /**
     * Adds a field detail.
     *
     * @param $objectField
     * @return ObjectFieldDetail
     */
    private function addFieldDetail($objectField): ObjectFieldDetail
    {
        $detail = new ObjectFieldDetail();
        $detail->setPosition($this->position);
        $detail->setField($objectField);
        $objectField->addDetail($detail);

        return $detail;
    }

    /**
     * Check device conditions for given setting.
     *
     * @param ObjectFieldDetail $detail
     * @param int $setting
     * @return bool
     */
    private function checkDeviceConditions(ObjectFieldDetail $detail, int $setting): bool
    {
        return (
            ($this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet() && $detail->getSettingSmall() == $setting)
            || ($this->mobileDetect->isTablet() && $detail->getSettingMedium() == $setting)
            || (!$this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet() && $detail->getSettingLarge() == $setting)
        );
    }
}