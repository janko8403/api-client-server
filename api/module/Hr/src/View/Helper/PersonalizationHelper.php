<?php

namespace Hr\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;
use Hr\Personalization\PersonalizationService;

/**
 * Description of PersonalizationHelper
 *
 * @author daniel
 */
class PersonalizationHelper extends AbstractHelper
{
    /**
     *
     * @var type PersonalizationService
     */
    private $personalizationService;

    /**
     *
     * @param PersonalizationService $personalizationService
     */
    public function __construct(PersonalizationService $personalizationService)
    {
        $this->personalizationService = $personalizationService;
    }

    /**
     *
     * @param \Hr\View\Helper\String $personalizationName
     * @return type bool
     */
    public function __invoke(string $personalizationName)
    {
        return $this->personalizationService->active($personalizationName);
    }
}
