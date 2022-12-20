<?php


namespace Hr\Service;


use Doctrine\Persistence\ObjectManager;

trait ObjectManagerTrait
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * ZipCodeService constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }
}