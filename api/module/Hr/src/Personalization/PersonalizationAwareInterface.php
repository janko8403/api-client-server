<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 14.11.2016
 * Time: 14:52
 */

namespace Hr\Personalization;


interface PersonalizationAwareInterface
{
    /**
     * @param PersonalizationService $service
     * @return PersonalizationAwareInterface
     */
    public function setPersonalizationService(PersonalizationService $service);
}