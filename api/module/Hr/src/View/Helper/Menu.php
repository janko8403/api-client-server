<?php

namespace Hr\View\Helper;

use Hr\Menu\MenuService;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Permissions\Acl\Exception\InvalidArgumentException;
use Laminas\View\Helper\AbstractHelper;

class Menu extends AbstractHelper
{
    /**
     * @var MenuService
     */
    private $menuService;

    /**
     * @var AbstractAdapter
     */
    private $cacheAdapter;

    /**
     * @var \Mobile_Detect
     */
    private $mobileDetect;

    /**
     * @var int
     */
    private $positionId;

    /**
     * Menu constructor.
     *
     * @param MenuService     $menuService
     * @param AbstractAdapter $cacheAdapter
     * @param \Mobile_Detect  $mobileDetect
     * @param int             $positionId
     */
    public function __construct(
        MenuService     $menuService,
        AbstractAdapter $cacheAdapter,
        \Mobile_Detect  $mobileDetect,
        int             $positionId
    )
    {
        $this->cacheAdapter = $cacheAdapter;
        $this->menuService = $menuService;
        $this->mobileDetect = $mobileDetect;
        $this->positionId = $positionId;
    }

    public function __invoke(array $params)
    {
        $key = $this->generateCacheKey();

        try {
            if ($this->cacheAdapter->hasItem($key)) {
                return $this->cacheAdapter->getItem($key);
            } else {
                $this->menuService->init();
                $html = $this->view->navigation()->menu()->renderPartialWithParams($params);
                $this->cacheAdapter->setItem($key, $html);

                return $html;
            }
        } catch (InvalidArgumentException $ex) {
            return '';
        }
    }

    private function generateCacheKey()
    {
        return sprintf(
            'UserMenu_p%d_m%d_t%d',
            $this->positionId,
            (int)$this->mobileDetect->isMobile(),
            (int)$this->mobileDetect->isTablet()
        );
    }
}
