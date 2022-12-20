<?php

namespace Hr\Dictionary;

use Doctrine\Persistence\ObjectManager;
use Products\Entity\ProductSubgroup;
use Hr\Entity\Dictionary;
use Hr\Entity\DictionaryDetails;
use Hr\Entity\Subchain;

class DictionaryService
{
    const CHILDREN = [
        // parent => child
        Dictionary::DIC_FORMATS => ['id' => Dictionary::DIC_SUBFORMATS, 'name' => 'subformats'],
        Dictionary::DIC_CHAINS => ['id' => 'subchains', 'name' => 'subchains'],
        Dictionary::DIC_SIZES => ['id' => Dictionary::DIC_SUBSIZES, 'name' => 'subsizes'],
        'productgroup' => ['id' => 'productgroup', 'name' => 'productgroup'],
    ];

    private ObjectManager $objectManager;

    /**
     * DictionaryService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public static function getChildDictionary(string $parent)
    {
        return self::CHILDREN[$parent] ?? null;
    }

    /**
     * Gets dependant values from child dictionary.
     *
     * @param string $dictionary
     * @param array  $values
     * @return array
     */
    public function getDependantValues(string $dictionary, array $values): array
    {
        $data = [];

        // different fetch method for subchains -> stored in a different table
        switch ($dictionary) {
            case 'subchains':
                $dictionaryDetails = empty($values)
                    ? $this->objectManager->getRepository(Subchain::class)->findBy(['isActive' => 1])
                    : $this->objectManager->getRepository(Subchain::class)->findBy(['chain' => $values, 'isActive' => 1]);
                break;
            case 'subgroups':
                $dictionaryDetails = empty($values)
                    ? $this->objectManager->getRepository(ProductSubgroup::class)->findBy(['isActive' => 1])
                    : $this->objectManager->getRepository(ProductSubgroup::class)->findBy(['group' => $values, 'isActive' => 1], ['value' => 'ASC']);
                break;
            default:
                // old dictionary / subdictionary
                if (!empty($values)) {
                    $dictionaryDetails = $this->objectManager->getRepository(DictionaryDetails::class)->findBy(['parent' => $values, 'isactive' => 1]);
                }
        }

        if (isset($dictionaryDetails)) {
            foreach ($dictionaryDetails as $detail) {
                $data[$detail->getId()] = $detail->getName();
            }
        }

        return $data;
    }
}