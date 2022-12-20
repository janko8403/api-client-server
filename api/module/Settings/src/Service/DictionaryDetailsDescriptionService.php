<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 19.09.17
 * Time: 18:17
 */

namespace Settings\Service;


use Doctrine\Persistence\ObjectManager;
use Hr\Entity\DictionaryDetailsDescription;

class DictionaryDetailsDescriptionService
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(
        ObjectManager $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    public function getDescriptionValues($id)
    {
        $description = $this->objectManager->getRepository(DictionaryDetailsDescription::class)->findBy(['dictionaryDetail' => $id]);

        $tmp = [];
        foreach ($description as $d) {
            $tmp[$d->getKey()] = $d->getName();
        }

        return $tmp;
    }
}