<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.07.2017
 * Time: 16:25
 */

namespace Customers\Service;

use Customers\Entity\CustomerTemplate;
use Doctrine\Persistence\ObjectManager;

class TemplateService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * TemplateService constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Saves customer template data
     *
     * @param array $data
     */
    public function save(array $data)
    {
        if (empty($data['customer']['customerGroups'])) {
            $data['customer']['customerGroups'] = '';
        }
        if (empty($data['customer']['subchain'])) {
            $data['customer']['subchain'] = '';
        }

        foreach ($data as $key => $values) {
            if ($key != 'save') {
                foreach ($values as $field => $value) {
                    $templateValue = $this->objectManager->getRepository(CustomerTemplate::class)
                        ->findOneBy(['key' => $key, 'field' => $field]);

                    if (!$templateValue) {
                        $templateValue = new CustomerTemplate();
                        $templateValue->setKey($key);
                        $templateValue->setField($field);
                    }

                    if (is_array($value)) {
                        $value = $value[0];
                    }
                    $templateValue->setValue($value);
                    $this->objectManager->persist($templateValue);
                }
            }
        }

        $this->objectManager->flush();
    }

    /**
     * Fetches customer template data
     *
     * @return array
     */
    public function fetchAll() : array
    {
        $data = [];
        $values = $this->objectManager->getRepository(CustomerTemplate::class)->findAll();
        foreach ($values as $value) {
            $data[$value->getKey()][$value->getField()] = $value->getValue();
        }

        return $data;
    }
}