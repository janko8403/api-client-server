<?php


namespace Hr\Location;


use Hr\Entity\ZipCode;
use Hr\Service\ObjectManagerTrait;

class ZipCodeService
{
    use ObjectManagerTrait;

    /**
     * Fetches zip code data.
     *
     * @param string $zipCode
     * @return array
     */
    public function fetchByZipCode(string $zipCode): array
    {
        return $this->objectManager->getRepository(ZipCode::class)
            ->findBy(['code' => $zipCode]);
    }

    /**
     * Fetches first zip code data.
     *
     * @param string $zipCode
     * @return ZipCode|null
     */
    public function fetchOneByZipCode(string $zipCode): ?ZipCode
    {
        $codes = $this->fetchByZipCode($zipCode);

        return $codes[0] ?? null;
    }
}