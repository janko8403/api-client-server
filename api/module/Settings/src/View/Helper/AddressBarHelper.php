<?php

namespace Settings\View\Helper;

use Settings\Service\AddressBarService;
use Laminas\View\Helper\AbstractHelper;

class AddressBarHelper extends AbstractHelper
{
    /**
     * @var AddressBarService
     */
    private $addressBarService;

    /**
     * @var array
     */
    private $identity;

    /**
     * AddressBar constructor.
     * @param AddressBarService $addressBarService
     * @param array $identity
     */
    public function __construct(AddressBarService $addressBarService, array $identity)
    {
        $this->addressBarService = $addressBarService;
        $this->identity = $identity;
    }

    public function __invoke(int $customerId = null)
	{
	    if (!is_null($customerId)) {
	        $html = $this->addressBarService->getBarCustomer($customerId, $this->identity['regionDicId']);
        } else {
	        $html = $this->addressBarService->getBarUser($this->identity['id']);
        }

	    return $html;
	}
}