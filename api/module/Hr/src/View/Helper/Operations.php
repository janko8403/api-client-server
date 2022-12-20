<?php

namespace Hr\View\Helper;

use Hr\Acl\AclService;
use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;

class Operations extends AbstractHelper
{
    private AclService $aclService;

    /**
     * Operations constructor.
     *
     * @param AclService $aclService
     */
    public function __construct(AclService $aclService)
    {
        $this->aclService = $aclService;
    }

    /**
     * Generates operations dropdown. $params array can have the following keys:
     * - icon - Glyphicon do display
     * - label - Menu item's label
     * - route - Route used to create link
     * - resource - Resource to check for permissions
     * - mode - Resource mode to check
     * - params - (optional) Route params
     * - id - (optional) Link's id
     * - rewrite_qs - (optional) bool - rewrite query string
     * - class - (optional) CSS class
     * - query_params - (optional) quest string params
     *
     * @param array $params
     * @return string
     * @throws \Exception
     */
    function __invoke(array $params): string
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $fontAwesomeHelper = $view->getHelperPluginManager()->get('fontAwesome');
        $urlHelper = $view->getHelperPluginManager()->get('url');
        $queryParamsHelper = $view->getHelperPluginManager()->get('queryParams');

        $html = <<<HTML
        <div class="btn-group my-2">
             %s
        </div>
HTML;

        /** @noinspection HtmlUnknownTarget */
        $li = '<a href="%s" id="%s" class="%s btn btn-outline-secondary" %s>%s</a></li>';
        $lis = [];
        foreach ($params as $p) {
            if (!empty($p)) {
                if (!$this->aclService->isAllowedByRoute($p['route'])) {
                    continue;
                }

                $label = !empty($p['icon']) ? $fontAwesomeHelper($p['icon']) . ' ' : '';
                $label .= $this->translator->translate($p['label']);

                $class = $p['class'] ?? '';
                $id = $p['id'] ?? '';

                $dataAttributes = '';
                if (isset($p['data'])) {
                    foreach ($p['data'] as $key => $value) {
                        $dataAttributes .= sprintf('data-%s="%s" ', $key, $value);
                    }
                }
                $queryParams = ($p['rewrite_qs'] ?? false) ? $queryParamsHelper() : [];
                if (!empty($p['query_params'])) {
                    $queryParams = $p['query_params'];
                }

                $lis[] = sprintf(
                    $li,
                    $urlHelper($p['route'], $p['params'] ?? [], ['query' => $queryParams]),
                    $id,
                    $class,
                    $dataAttributes, $label
                );
            }
        }

        return count($lis) ? sprintf($html, implode('', $lis)) : '';
    }
}
