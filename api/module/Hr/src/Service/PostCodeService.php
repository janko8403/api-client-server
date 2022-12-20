<?php

namespace Hr\Service;

use Doctrine\Persistence\ObjectManager;
use Hr\Entity\PostCodeCoordinate;
use Hr\Map\MapService;

class PostCodeService
{
    private ObjectManager $objectManager;

    private MapService $mapService;

    /**
     * @param ObjectManager $objectManager
     * @param MapService    $mapService
     */
    public function __construct(ObjectManager $objectManager, MapService $mapService)
    {
        $this->objectManager = $objectManager;
        $this->mapService = $mapService;
    }

    /**
     * Finds post code.
     *
     * @param string $code
     * @return PostCodeCoordinate|null
     */
    public function findCode(string $code): ?PostCodeCoordinate
    {
        return $this->objectManager->getRepository(PostCodeCoordinate::class)->findOneBy(['code' => $code]);
    }

    /**
     * Creates post code (includes geocoding).
     *
     * @param string $code
     * @return PostCodeCoordinate
     */
    public function createCode(string $code): PostCodeCoordinate
    {
        $address = "Polska, $code";
        $coordinates = $this->mapService->geocode($address);

        $postCode = new PostCodeCoordinate();
        $postCode->setCode($code);
        $postCode->setLat(!empty($coordinates['lat']) ? $coordinates['lat'] : null);
        $postCode->setLng(!empty($coordinates['lng']) ? $coordinates['lng'] : null);
        $this->objectManager->persist($postCode);
        $this->objectManager->flush();

        return $postCode;
    }
}