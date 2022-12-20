<?php
/**
 * Created by PhpStorm.
 * User: danie
 * Date: 20.03.2017
 * Time: 17:24
 */

namespace Hr\Location;


class LocationService
{
    const LOCATION_NO_DATA = 0;
    const LOCATION_NO_BIG_DIFFERENCE = 1;
    const LOCATION_NO_DIFFERENCE = 2;
    const R = 6371.0e3;

    public function getOrthogonalDifference($photo, $customer)
    {
        if ($photo->getLatitude() < 0 || $photo->getLongitude() < 0 || $customer->getLatitude() < 0 || $customer->getLongitude() < 0) {
            return self::LOCATION_NO_DATA;
        } else {
            $latFrom = deg2rad($photo->getLatitude());
            $lonFrom = deg2rad($photo->getLongitude());
            $latTo = deg2rad($customer->getLatitude());
            $lonTo = deg2rad($customer->getLongitude());

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

            return ($angle * self::R > 1000) ? self::LOCATION_NO_BIG_DIFFERENCE : self::LOCATION_NO_DIFFERENCE;
        }

    }
}