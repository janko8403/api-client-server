<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 07.11.2018
 * Time: 16:07
 */

namespace Hr\View\Helper;

use Users\Service\UserService;
use Laminas\View\Helper\AbstractHelper;

class IsSubordinateOf extends AbstractHelper
{
    /**
     * @var UserService
     */
    private $service;

    /**
     * IsSubordinateOf constructor.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function __invoke(int $subordinateId, int $userId)
    {
        return $this->service->isSubordinateOf($subordinateId, $userId);
    }

}