<?php

/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.07.2017
 * Time: 12:20
 */

namespace Settings\Service;

use Configuration\Entity\Position;
use Configuration\Entity\Resource;
use Configuration\Entity\ResourcePosition;
use Doctrine\Persistence\ObjectManager;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;

class DictionaryService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * DictionaryService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function save($detail)
    {
        $this->objectManager->persist($detail);

        /*
         * @todo Refactor to factory/adapter if new types are needed
         */
        if ($detail instanceof Position) {
            $this->addPositionToResources($detail);
        }

        $this->objectManager->flush();
    }

    /**
     * Check wheather given value exists in a dictionary.
     *
     * @param Dictionary $dictionary
     * @param string     $value
     * @return bool
     */
    public function checkValueExists(Dictionary $dictionary, string $value): bool
    {
        $detail = $this->objectManager->getRepository(DictionaryDetails::class)->findOneBy(['dictionary' => $dictionary, 'name' => $value]);

        return (bool)$detail;
    }

    /**
     * Adds resources to a new position.
     *
     * @param Position $detail
     */
    private function addPositionToResources(Position $detail)
    {
        // only when adding position perform action
        if (!$detail->getId()) {
            $resources = $this->objectManager->getRepository(Resource::class)->findAll();
            foreach ($resources as $resource) {
                $rp = new ResourcePosition();
                $rp->setPosition($detail);
                $rp->setResource($resource);
                $detail->addResourcePosition($rp);
            }
        }
    }
}