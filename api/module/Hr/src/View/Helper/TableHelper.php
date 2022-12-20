<?php

namespace Hr\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;
use Laminas\Mvc\I18n\Translator;
use Hr\Acl\AclService;
use Hr\Table\TableService;
use Laminas\View\Renderer\PhpRenderer;

class TableHelper extends AbstractHelper
{
    private AclService $aclService;

    /**
     * TableHelper constructor.
     *
     * @param Translator $translator
     * @param AclService $aclService
     */
    public function __construct(Translator $translator, AclService $aclService)
    {
        $this->translator = $translator;
        $this->aclService = $aclService;
    }

    /**
     * Generates HTML data table.
     *
     * @param TableService $tableService
     * @param string       $route Route name; MUST be the same as resource name
     * @param bool         $displayPagination
     * @param array        $routeParams
     * @return string
     * @throws \Exception
     */
    function __invoke(TableService $tableService, string $route, bool $displayPagination = true, array $routeParams = []): string
    {
        $editRoute = $tableService->getEditRoute() ?? $route . '/edit';
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $ordHelper = $view->getHelperPluginManager()->get('ord');
        $urlHelper = $view->getHelperPluginManager()->get('url');
        $fontAwesomeHelper = $view->getHelperPluginManager()->get('fontAwesome');
        $paginationControlHelper = $view->getHelperPluginManager()->get('paginationControl');
        $partialHelper = $view->getHelperPluginManager()->get('partial');
        $isAllowedHelper = $view->getHelperPluginManager()->get('isAllowed');

        // check whether table rows should be selectable
        $isSelectable = false;
        $selectableResource = $tableService->getResourceSelectable();
        if ($selectableResource) {
            $isSelectable = $isAllowedHelper($selectableResource);
        }

        $html = '<div class="data-table"><table class="table table-condensed table-hover table-borderless %s" id="%s"><thead><tr>';
        $html .= $isSelectable ? '<th></th>' : '';
        $html .= '<th>%s</th>';
        $html = sprintf(
            $html,
            $tableService->getAttribute('class'),
            $tableService->getId(),
            $this->translator->translate('Lp')
        );

        $hasRowActions = $tableService->getOption(TableService::OPT_SHOW_EDIT) || (count($tableService->getRowActions()) > 0);

        foreach ($tableService->getColumns() as $c) {
            if (!empty($c['sort'])) {
                $html .= '<th>'
                    . $partialHelper(
                        'sort',
                        [
                            'label' => $this->translator->translate($c['label']),
                            'field' => $c['sort'],
                            'queryParams' => $tableService->getQueryParams(),
                            'route' => $route,
                        ]
                    )
                    . '</th>';
            } else {
                $html .= '<th>' . $this->translator->translate($c['label']) . '</th>';
            }
        }

        if ($hasRowActions) {
            $html .= '<th></th>';
        }

        $html .= '</tr></thead><tbody>';

        $data = $tableService->getPaginator();
        foreach ($data as $i => $d) {
            // check if there are any row attributes specified
            $attributes = '';
            $rowAttributes = $tableService->getRowAttributes();
            if (!empty($rowAttributes)) {
                foreach ($rowAttributes as $name => $value) {
                    $attributes .= "$name='";
                    $attributes .= is_callable($value) ? $value($d) : $value;
                    $attributes .= "' ";
                }
            }

            $html .= "<tr data-id='{$d->getId()}' $attributes>";
            $html .= $isSelectable
                ? "<td class='text-center'><input type='checkbox' name='rows[]' value='{$d->getId()}' class='chk-row' /></td>"
                : '';
            $html .= "<td>" . $ordHelper($data, $i) . '</td>';

            foreach ($tableService->getColumns() as $name => $col) {
                $html .= '<td>';

                if (!isset($col['value'])) {
                    // value closure is empty, use standard getter
                    $value = $d->{'get' . ucfirst($name)}();
                } elseif (is_callable([$tableService, $col['value']], true)) {
                    // value is a TableService class child's method
                    $value = call_user_func([$tableService, $col['value']], $d);
                } elseif (is_callable($col['value'])) {
                    // value is a closure, pass entity as param
                    $value = $col['value']($d);
                } else {
                    // otherwise just display the value
                    $value = $col['value'];
                }

                // use view helper if provided
                if (!empty($col['helper'])) {
                    $helperName = $col['helper']['name'] ?? $col['helper'];
                    $helper = $view->getHelperPluginManager()->get($helperName);
                    $args = $col['helper']['params'] ?? [];
                    $value = $helper($value, ...$args);
                }

                $html .= $value . '</td>';
            }

            if ($hasRowActions) {
                $html .= '<td>';

                // generate edit link
                if (
                    /*$this->aclService->isAllowedByRoute($editRoute)
                    &&*/ $tableService->getOption(TableService::OPT_SHOW_EDIT)
                ) {
                    if (
                        $this->aclService->isAllowedByRoute($editRoute)
                        && $tableService->getOptionCondition(TableService::OPT_SHOW_EDIT)($d)
                    ) {
                        $html .= '<a href="'
                            . $urlHelper($editRoute, ['id' => $d->getId()])
                            . '">'
                            . $fontAwesomeHelper('pencil fa-' . $tableService->getIconSize())
                            . '</a>';
                    }
                }

                // generate row actions
                $rowActions = $tableService->getRowActions();
                foreach ($rowActions as $action) {
                    $params = $action['params']($d);

                    if (
                        $action['condition']($d)
                        && $this->aclService->isAllowedByRoute($params['route'])
                    ) {
                        if (isset($params['data'])) {
                            array_walk($params['data'], function (&$item, $key) {
                                $item = sprintf('data-%s="%s"', $key, $item);
                            });
                        }

                        $html .= sprintf(
                            ' <a href="%s" title="%s" class="%s" %s>%s</a>',
                            $urlHelper($params['route'], $params['route-params'], ['query' => $params['query-params'] ?? []]),
                            $this->translator->translate($params['title']),
                            $params['class'] ?? '',
                            (!empty($params['data']) ? implode(' ', $params['data']) : ''),
                            $fontAwesomeHelper("$params[icon] fa-" . $tableService->getIconSize())
                        );
                    }
                }

                $html .= '</td></tr>';
            } else {
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table>';

        if ($displayPagination) {
            $html .= $paginationControlHelper(
                $tableService->getPaginator(),
                'Sliding',
                'paginator',
                ['route' => $route, 'params' => $tableService->getQueryParams(), 'routeParams' => $routeParams]
            );
        }

        $html .= '</div>';

        return $html;
    }
}