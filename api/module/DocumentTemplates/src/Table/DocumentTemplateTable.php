<?php

namespace DocumentTemplates\Table;

use DocumentTemplates\Entity\DocumentTemplate;
use Hr\Table\TableService;
use Hr\View\Helper\DateTime;

class DocumentTemplateTable extends TableService
{
    public function init()
    {
        $this->setId('documentTemplateTable');
        $this->configureColumns([
            'name' => [
                'label' => 'Nazwa',
                'sort' => 't.name', // sort by field (with alias)
            ],
            'type' => [
                'label' => 'Typ',
                'value' => 'getTypeName',
            ],
            'creationDate' => [
                'label' => 'Data utworzenia',
                'helper' => ['name' => 'dt', 'params' => [DateTime::DATETIME_SHORT]],
                'sort' => 't.creationDate',
            ],
            'isActive' => [
                'label' => 'Aktywne',
                'helper' => 'chk',
            ],
        ]);

        $this->addRowAction(
            function (DocumentTemplate $template) {
                return [
                    'title' => 'Podgląd szablonu',
                    'icon' => 'file-pdf-o',
                    'route' => 'document-templates/preview',
                    'route-params' => ['action' => 'activate', 'id' => $template->getId()],
                ];
            }
        );
        $this->addRowAction(
            function (DocumentTemplate $template) {
                return [
                    'title' => 'Aktywuj',
                    'icon' => 'play',
                    'class' => 'a-activate',
                    'route' => 'document-templates/default',
                    'route-params' => ['action' => 'activate', 'id' => $template->getId()],
                ];
            }, function (DocumentTemplate $template) {
            return !$template->getIsActive();
        }
        );
        $this->addRowAction(
            function (DocumentTemplate $template) {
                return [
                    'title' => 'Dezaktywuj',
                    'icon' => 'pause',
                    'class' => 'a-deactivate',
                    'route' => 'document-templates/default',
                    'route-params' => ['action' => 'deactivate', 'id' => $template->getId()],
                ];
            }, function (DocumentTemplate $template) {
            return $template->getIsActive();
        }
        );
    }

    public function getTypeName(DocumentTemplate $documentTemplate)
    {
        return $documentTemplate->getTypeName();
    }
}