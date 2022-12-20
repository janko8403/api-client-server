<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 26.06.2017
 * Time: 16:42
 */

namespace Hr\Acl;

use Configuration\Entity\Position;
use Configuration\Entity\Resource;
use Configuration\Entity\ResourcePosition;
use Doctrine\Persistence\ObjectManager;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole;
use Laminas\Router\Http\RouteMatch;

class AclService
{
    const HEADER_ID = 'header';
    const HEADER_SL_LOGO = 'header:logoSL';
    const HEADER_SL_PIZZA = 'header:pizzaSL';
    const HEADER_BREADCRUMB = 'header:breadcrumb';
    const HEADER_CLIENT_LOGO = 'header:clientLogo';
    const HEADER_TRIPPLE_DOT = 'header:tripleDot';
    const MENU_AVATAR = 'menu:avatar';
    const RESOURCE_COMMISSIONS = 'mvc:commissions';

    const USER_REJECTED_MONITORINGS = 'mvc:reportsevaluation:user:rejected';
    const REPORTS_EVELUATION_DETAILS = 'mvc:reportsevaluation:user:details';
    const REPORTS_EVALUATION_FULFILMENT_COMMENT = 'reportsevaluation:fulfilmentComment';
    const REPORTS_EVALUATION_REJECT_WITH_EMAIL = 'reportsevaluation:rejectWithEmail';
    const REPORTS_EVALUATION_REJECT_CONTEXT = 'reportsevaluation:rejectContext';
    const REPORTS_EVALUATION_REJECT_QUANTITY = 'reportsevaluation:rejectQuantity';
    const REPORTS_EVALUATION_REJECT_QUALITY = 'reportsevaluation:rejectQuality';
    const REPORTS_EVALUATION_REJECT_CONTENT = 'reportsevaluation:rejectContent';
    const REPORTS_EVALUATION_REJECT_OTHER = 'reportsevaluation:rejectOthers';
    const REPORTS_EVALUATION_ACCEPT = 'reportsevaluation:accept';
    const REPORTS_EVALUATION_IMAGE = 'mvc:reportsevaluation:index:image';
    const REPORTS_EVALUATION_DOWNLOAD = 'mvc:reportsevaluation:index:download';
    const REPORTS_EVALUATION_FULFILMENT_TIME = 'reportsevaluation:fulfilmentTime';
    const REPORTS_EVALUATION_FULFILMENT_LOCATION = 'reportsevaluation:fulfilmentLocation';
    const REPORTS_EVALUATION_SEND_CONFIG = 'reportsevaluation:sendConfig';
    const REPORTS_EVALUATION_PHOTO_PACKAGE = 'mvc:reportsevaluation:photopackage';

    //tabela
    const REPORTS_EVALUATION_MONITORING_NAME = 'reportsevaluation:monitoringName';
    const REPORTS_EVALUATION_DATE_AND_TIME = 'reportsevaluation:dateAndTime';
    const REPORTS_EVALUATION_CHAIN = 'reportsevaluation:chain';
    const REPORTS_EVALUATION_SUBCHAIN = 'reportsevaluation:subchain';
    const REPORTS_EVALUATION_CUSTOMER_NAME = 'reportsevaluation:customerName';
    const REPORTS_EVALUATION_CUSTOMER_ADDRESS = 'reportsevaluation:customerAddress';
    const REPORTS_EVALUATION_SCORE = 'reportsevaluation:score';
    const REPORTS_EVALUATION_USER_NAME = 'reportsevaluation:userName';

    const START_END_DAY = 'mvc:monitoringfulfilments:index:startendday';

    const CUSTOMER_DETAILS_ORDER = 'customer:details:order';

    const PRODUCTS_DISCOUNTS_ACCEPTANCE = 'products:discount:acceptance';

    const COMMISSIONS_MANAGEMENT_BLOCK_GRADE = 'commissionmanagement:block';
    const COMMISSIONS_MANAGEMENT_50_PCT = 'commissionmanagement:50pct';

    const CUSTOMER_APP_FUTURE_COMMISSIONS = 'customer:application:future-commissions';
    const CUSTOMER_APP_RATE = 'customer:application:rate-commission';
    const CUSTOMER_APP_CREATE = 'customer:application:create-commission';

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \Mobile_Detect
     */
    private $mobileDetect;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * @var int
     */
    private $positionId;

    /**
     * Roles array.
     *
     * @var array
     */
    private $roles = [];

    /**
     * @var array
     */
    private $resourceNames = [];

    /**
     * @var Acl
     */
    private $acl;

    /**
     * @return Acl
     */
    public function getAcl(): Acl
    {
        return $this->acl;
    }

    /**
     * AclService constructor.
     *
     * @param ObjectManager   $objectManager
     * @param \Mobile_Detect  $mobileDetect
     * @param AbstractAdapter $cacheAdapter
     * @param int             $positionId
     */
    public function __construct(
        ObjectManager   $objectManager,
        \Mobile_Detect  $mobileDetect,
        AbstractAdapter $cacheAdapter,
        int             $positionId
    )
    {
        $this->objectManager = $objectManager;
        $this->mobileDetect = $mobileDetect;
        $this->cacheAdapter = $cacheAdapter;
        $this->positionId = $positionId;

        // setup cache - ACL
        $cacheKey = sprintf(
            'acl_m%d_t%d_p%d',
            (int)$mobileDetect->isMobile(),
            (int)$mobileDetect->isTablet(),
            $positionId
        );

        if (!$cacheAdapter->hasItem($cacheKey)) {
            $this->acl = new Acl();
            $this->createRoles();
            $this->createResources();
            $this->setPermissions();
            $cacheAdapter->setItem($cacheKey, $this->acl);
        } else {
            $this->acl = $cacheAdapter->getItem($cacheKey);
        }
    }

    /**
     * Checks wheather given group has access to a resource.
     *
     * @param int   $roleId
     * @param array $resourceParts Array with 3 fields (module, controller, action)
     * @return bool
     */
    public function hasAccess(int $roleId, array $resourceParts): bool
    {
        $resource = "mvc:$resourceParts[module]:$resourceParts[controller]:$resourceParts[action]";
        if ($this->acl->hasResource($resource)) {
            return $this->acl->isAllowed($roleId, $resource);

        }

        $resource = "mvc:$resourceParts[module]:$resourceParts[controller]";
        if ($this->acl->hasResource($resource)) {
            return $this->acl->isAllowed($roleId, $resource);

        }

        $resource = "mvc:$resourceParts[module]";
        if ($this->acl->hasResource($resource)) {
            return $this->acl->isAllowed($roleId, $resource);
        }

        return false;
    }

    /**
     * Get resource parts (module, controller, action).
     *
     * @param RouteMatch $route
     * @return array
     */
    public function getResourceParts(RouteMatch $route): array
    {
        [$module, $c, $controller] = explode('\\', $route->getParam('controller'));
        $action = $route->getParam('action');

        return [
            'module' => strtolower($module),
            'controller' => strtolower(str_replace('Controller', '', $controller)),
            'action' => strtolower($action),
        ];
    }

    /**
     * Checks if a user has access to a resource (by route).
     *
     * @param string $route
     * @return bool
     * @throws \Exception
     */
    public function isAllowedByRoute(string $route): bool
    {
        if (isset($this->resourceNames[$route])) {
            $name = $this->resourceNames[$route];
        } else {
            $resource = $this->objectManager->getRepository(Resource::class)->findOneBy(['route' => $route]);

            if (empty($resource)) {
                $parts = explode('/', $route);
                array_pop($parts); // remove last element - resource with full name doesn't exist

                while (count($parts) > 0) {
                    $tempRoute = implode('/', $parts);
                    $resource = $this->objectManager->getRepository(Resource::class)->findOneBy(['route' => $tempRoute]);
                    if (!empty($resource)) {
                        break;
                    }

                    array_pop($parts);
                }

                if (empty($resource)) {
                    return false;
//                    throw new \Exception("No resource for route `$route`");
                }
            }

            $name = $resource->getName();
            $this->resourceNames[$route] = $name;
        }

        return $this->acl->isAllowed($this->positionId, $name);
    }

    /**
     * Configures ACL roles
     */
    private function createRoles()
    {
        $roles = $this->objectManager->getRepository(Position::class)->findBy(['isActive' => true]);
        foreach ($roles as $role) {
            $this->roles[$role->getId()] = new GenericRole($role->getId());
            $this->acl->addRole($this->roles[$role->getId()]);
        }
    }

    /**
     * Configures ACL resources
     */
    private function createResources()
    {
        $resources = $this->objectManager->getRepository(Resource::class)->findAll();
        foreach ($resources as $resource) {
            $name = $resource->getName();
            if (!$this->acl->hasResource($name)) {
                $this->acl->addResource($name);
            }
        }
    }

    /**
     * Sets ACL permissions for given position
     */
    private function setPermissions()
    {
        $resourcesAllowed = [];
        $resourcesDenied = [];
        $permissions = $this->objectManager->getRepository(ResourcePosition::class)->getPermissionsWithResourceNames($this->positionId);

        foreach ($permissions as $permission) {
            $resource = $permission->getResource();

            if (
                (!$this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet() && $resource->getSettingLarge() != Resource::SETTING_NO && $permission->getSettingLarge() != ResourcePosition::SETTING_NO)
                || ($this->mobileDetect->isTablet() && $resource->getSettingMedium() != Resource::SETTING_NO && $permission->getSettingMedium() != ResourcePosition::SETTING_NO)
                || ($this->mobileDetect->isMobile() && !$this->mobileDetect->isTablet() && $resource->getSettingSmall() != Resource::SETTING_NO && $permission->getSettingSmall() != ResourcePosition::SETTING_NO)
            ) {
                $resourcesAllowed[] = $permission->getResource()->getName();
            } else {
                $resourcesDenied[] = $permission->getResource()->getName();
            }
        }

        if (isset($this->roles[$this->positionId])) {
            $this->acl->allow($this->roles[$this->positionId], $resourcesAllowed);
            $this->acl->deny($this->roles[$this->positionId], $resourcesDenied);
        }
    }
}