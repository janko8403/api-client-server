<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 14.11.2016
 * Time: 14:54
 */

namespace Hr\Personalization;


trait PersonalizationAwareTrait
{
    /**
     * @var PersonalizationService
     */
    protected $personalizationService = null;

    /**
     * @param PersonalizationService $personalizationService
     * @return $this
     */
    public function setPersonalizationService(PersonalizationService $personalizationService)
    {
        $this->personalizationService = $personalizationService;

        return $this;
    }
}