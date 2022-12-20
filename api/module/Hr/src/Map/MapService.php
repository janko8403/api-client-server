<?php

namespace Hr\Map;

use Doctrine\Persistence\ObjectManager;
use Laminas\Http\Client;
use Laminas\Json\Json;

class MapService
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * MapController constructor.
     *
     * @param ObjectManager $objectManager
     * @param string        $apiKey
     */
    public function __construct(ObjectManager $objectManager, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->objectManager = $objectManager;
    }

    public function geocode(string $address)
    {
        $url = sprintf(
            'https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s',
            urlencode($address),
            $this->apiKey
        );
        $client = new Client($url);
        $response = $client->send();

        if ($response->getStatusCode() == 200) {
            $data = Json::decode($response->getBody(), Json::TYPE_ARRAY);
            if (isset($data['results'][0]['geometry']['location'])) {
                return $data['results'][0]['geometry']['location'];
            }
        }

        return null;
    }

    public function calculateDistanceMatrix(): array
    {
        $conn = $this->objectManager->getConnection();
        $sqls = [
            'ALTER TABLE `distanceMatrixCustomers` DROP INDEX `customerToIdx`',
            'ALTER TABLE `distanceMatrixCustomers` DROP INDEX `customerFromIdx`',
            'ALTER TABLE `distanceMatrixCustomers` DROP INDEX `distanceIdx`',
            'TRUNCATE distanceMatrixCustomers',
            'insert into distanceMatrixCustomers (customerFromId, customerFromLat, customerFromLng, customerToId, customerToLat, customerToLng, distance)
    select c1.id, c1.latitude, c1.longitude, c2.id, c2.latitude, c2.longitude,
    
     	111.045* DEGREES(ACOS(COS(RADIANS(c1.latitude))
         * COS(RADIANS(c2.latitude))
         * COS(RADIANS(c1.longitude) - RADIANS(c2.longitude))
         + SIN(RADIANS(c1.latitude))
         * SIN(RADIANS(c2.latitude))))
     	
    from customer c1
    cross join customer c2
    where c1.id < c2.id
    and c1.isActive = 1 and c2.isActive = 1
    and c1.latitude is not null and c1.longitude is not null
    and c2.latitude is not null and c2.longitude is not null
    and c1.latitude != 0 and c1.longitude != 0
    and c2.latitude != 0 and c2.longitude != 0',
            'ALTER TABLE `distanceMatrixCustomers` ADD INDEX `customerToIdx` (`customerToId`)',
            'ALTER TABLE `distanceMatrixCustomers` ADD INDEX `customerFromIdx` (`customerFromId`)',
            'ALTER TABLE `distanceMatrixCustomers` ADD INDEX `distanceIdx` (`distance`)',
        ];
        $count = 0;

        foreach ($sqls as $sql) {
            try {
                $conn->exec($sql);
                $count++;
            } catch (\Exception $e) {
                echo '<p>' . $sql . '</p>';
                echo "<p>" . $e->getMessage() . '</p>';
                echo '<hr>';
            }
        }

        return ['count' => $count, 'total' => count($sqls)];
    }
}