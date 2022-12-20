<?php

namespace Hr\Menu;

use Configuration\Entity\Resource;
use Doctrine\Persistence\ObjectManager;
use Laminas\Navigation\Navigation;
use Laminas\Navigation\Page\AbstractPage;
use Laminas\Navigation\Page\Mvc as MvcPage;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\View\Helper\Navigation as NavigationHelper;

class MenuService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var NavigationHelper
     */
    private $navigationHelper;

    /**
     * @var TreeRouteStack
     */
    private $router;

    /**
     * MenuService constructor.
     *
     * @param ObjectManager    $objectManager
     * @param NavigationHelper $navigationHelper
     * @param TreeRouteStack   $router
     */
    public function __construct(
        ObjectManager    $objectManager,
        NavigationHelper $navigationHelper,
        TreeRouteStack   $router
    )
    {
        $this->objectManager = $objectManager;
        $this->navigationHelper = $navigationHelper;
        $this->router = $router;
    }

    /**
     * Initilizes the menu.
     */
    public function init()
    {
        $pages = $this->buildMenuPages();
        $containter = new Navigation($pages);
        $this->injectRouter($containter, $this->router);

        $this->navigationHelper->setContainer($containter);
    }

    /**
     * Injects router instance into every page in structure.
     *
     * @param $container
     * @param $router
     */
    private function injectRouter($container, $router)
    {
        foreach ($container->getPages() as $page) {
            if ($page instanceof MvcPage) {
                $page->setRouter($router);
            }

            if ($page->hasPages()) {
                $this->injectRouter($page, $router);
            }
        }
    }

    /**
     * Builds hierarchical navigation page structure.
     *
     * @return array
     */
    private function buildMenuPages()
    {
        $pages = [];
        $parentResources = $this->objectManager->getRepository(Resource::class)->getParentUserMenuResources();

        /** @var Resource $resource */
        foreach ($parentResources as $resource) {
            $page = $this->createPage($resource);

            if (!$page) {
                continue;
            }

            if ($children = $resource->getUserMenuChildren()) {
                foreach ($children as $subresource) {
                    $subpage = $this->createPage($subresource);

                    if ($subpage) {
                        $page->addPage($subpage);
                    }
                }
            }

            $pages[] = $page;
        }

        return $pages;
    }

    /**
     * Creates navigation page instance from given resource.
     *
     * @param Resource $resource
     * @return mixed
     */
    private function createPage(Resource $resource)
    {
        if (empty($resource->getRoute())) {
            $page = AbstractPage::factory([
                'label' => $resource->getLabel(),
                'uri' => '#',
                'resource' => $resource->getName(),
                'icon' => $resource->getIcon(),
                'cookie' => $resource->getCookie(),
                'id' => $resource->getId(),
            ]);
        } else {
            $hasRoute = $this->hasRoute($resource->getRoute());

            if ($hasRoute) {
                $page = AbstractPage::factory([
                    'label' => $resource->getLabel(),
                    'route' => $resource->getRoute(),
                    'resource' => $resource->getName(),
                    'icon' => $resource->getIcon(),
                    'cookie' => $resource->getCookie(),
                    'id' => $resource->getId(),
                ]);
            }
        }

        return $page ?? null;
    }

    /**
     * Checks wheather given route name is valid.
     *
     * @param string $route
     * @return bool
     */
    private function hasRoute(string $route)
    {
        if ($this->router->hasRoute($route)) {
            return true;
        } else {
            $parts = explode('/', $route);

            $router = $this->router;
            while (count($parts)) {
                $part = array_shift($parts);

                if ($router->hasRoute($part)) {
                    return true;
                } else {
                    $router = $router->getRoute($part);

                    if (!$router) {
                        return false;
                    }
                }
            }
        }

        return false;
    }
}