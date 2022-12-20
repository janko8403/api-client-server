<?php

namespace Hr\View\Helper;

use Detection\MobileDetect;
use Laminas\Mvc\I18n\Translator;
use Laminas\Router\Http\RouteMatch;
use Laminas\View\Helper\AbstractHelper;

class Breadcrumbs extends AbstractHelper
{
    /**
     * @var array
     */
    private $breadcrumbs;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var MobileDetect
     */
    private $mobileDetect;

    /**
     * Bredcrumbs constructor.
     *
     * @param array        $breadcrumbs
     * @param Translator   $translator
     * @param MobileDetect $mobileDetect
     */
    public function __construct(array $breadcrumbs, Translator $translator, MobileDetect $mobileDetect)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->translator = $translator;
        $this->mobileDetect = $mobileDetect;
    }

    public function __invoke()
    {
        $translator = $this->translator;
        $urlHelper = $this->getView()->getHelperPluginManager()->get('url');

        array_walk($this->breadcrumbs, function (&$elem) use ($translator, $urlHelper) {
            if (is_array($elem)) {
                // breadcrumb is an array - create link

                if (empty($elem['route']) || empty($elem['label'])) {
                    throw new \Exception("Insufficient parameters for breadcrumbs part");
                }

                $elem = sprintf(
                    '<span><a href="%s">%s</a></span>',
                    $urlHelper->__invoke($elem['route'], $elem['params'] ?? []),
                    $translator->translate($elem['label'])
                );
            } else {
                $elem = '<span>' . $translator->translate($elem) . '</span>';
            }
        });

        if ($this->mobileDetect->isMobile()) {
            $this->breadcrumbs = (array)array_pop($this->breadcrumbs);
        }

        return implode('&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right"></i>&nbsp;&nbsp;&nbsp;', $this->breadcrumbs);
    }
}