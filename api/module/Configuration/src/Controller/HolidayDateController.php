<?php

namespace Configuration\Controller;

use Configuration\Entity\HolidayDate;
use Configuration\Form\HolidayDateForm;
use Configuration\Repository\HolidayDateRepository;
use Configuration\HolidayDate\HolidayDateService;
use Configuration\Table\HolidayDateTable;
use Doctrine\Persistence\ObjectManager;
use Laminas\Form\Element;
use Laminas\Http\PhpEnvironment\Response as HttpResponse;
use Laminas\Mvc\I18n\Translator;
use Laminas\View\Model\ViewModel;
use Hr\Controller\BaseController;

class HolidayDateController extends BaseController
{
    public function __construct(
        private ObjectManager      $objectManager,
        private HolidayDateTable   $holidayDateTable,
        private HolidayDateForm    $holidayDateForm,
        private HolidayDateService $holidayDateService,
        private Translator         $translator,
    )
    {
        $this->addBreadcrumbsPart('Wnioski')->addBreadcrumbsPart('Dni wolne od pracy');
    }

    /**
     * @throws \Exception
     */
    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();

        $this->holidayDateTable->init();

        /** @var HolidayDateRepository $holidayDateRepository */
        $holidayDateRepository = $this->objectManager->getRepository(HolidayDate::class);

        $this->holidayDateTable->setRepository($holidayDateRepository, $params);

        return new ViewModel(['table' => $this->holidayDateTable]);
    }

    /**
     * @throws \Exception
     */
    public function addAction(): ViewModel
    {
        $this->addBreadcrumbsPart('Dodaj');

        $holidayDate = new HolidayDate();

        $this->holidayDateForm->bind($holidayDate);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->holidayDateForm->bind($holidayDate);
            $this->holidayDateForm->setData($data);

            if ($this->holidayDateForm->isValid()) {
                $this->holidayDateService->add($holidayDate);

                $this->flashMessenger()->addSuccessMessage($this->translator->translate('Rekord został zapisany.'));
                $this->redirect()->toRoute('configuration/holidayDates');
            } else {
                $this->holidayDateForm->add([
                    'type' => Element\Button::class,
                    'name' => 'cancel',
                    'attributes' => [
                        'class' => 'btn btn-danger',
                        'value' => 'Cofnij zmiany',
                        'onclick' => 'window.location = window.location.href; return false;',
                    ],
                ]);
            }
        }
        return new ViewModel(['form' => $this->holidayDateForm]);
    }

    /**
     * @throws \Exception
     */
    public function removeAction(): HttpResponse
    {
        if ($this->getRequest()->isPost()) {
            $this->holidayDateService->remove($this->params('id'));
            $this->getResponse()->setContent('ok');
        }
        return $this->getResponse();
    }
}