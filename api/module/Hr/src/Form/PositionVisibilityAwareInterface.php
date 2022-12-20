<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 11.12.2018
 * Time: 14:59
 */

namespace Hr\Form;

use Doctrine\Persistence\ObjectManager;
use Settings\Service\PositionVisibilityService;

interface PositionVisibilityAwareInterface
{
    public function __construct(
        ObjectManager             $objectManager,
        PositionVisibilityService $positionVisibilityService,
        array                     $identity
    );
}