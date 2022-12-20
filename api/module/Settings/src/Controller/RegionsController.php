<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 12.07.2018
 * Time: 17:50
 */

namespace Settings\Controller;

use Settings\Form\RegionAssignForm;
use Settings\Form\SubregionAssignForm;
use Settings\Service\RegionService;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RegionsController extends AbstractActionController
{
    /**
     * @var RegionService
     */
    private $service;

    /**
     * @var SubregionAssignForm
     */
    private $subregionAssignForm;

    /**
     * @var RegionAssignForm
     */
    private $regionAssignForm;

    /**
     * RegionsController constructor.
     * @param RegionService $service
     * @param SubregionAssignForm $subregionAssignForm
     * @param RegionAssignForm $regionAssignForm
     */
    public function __construct(
        RegionService $service,
        SubregionAssignForm $subregionAssignForm,
        RegionAssignForm $regionAssignForm
    ) {
        $this->service = $service;
        $this->subregionAssignForm = $subregionAssignForm;
        $this->regionAssignForm = $regionAssignForm;
    }

    public function indexAction()
    {
        $vm = new ViewModel([
            'macroregions' => $this->service->getMacroRegions(),
            'subregions' => $this->service->getSubregions(),
            'regions' => $this->service->getRegions(),
            'unassigned_regions' => $this->service->getUnassignedRegions(),
            'unassigned_subregions' => $this->service->getUnassignedSubregions(),
        ]);
        if ($this->getRequest()->isXmlHttpRequest()) {
            $vm->setTerminal(true);
        }

        return $vm;
    }

    public function assignSubregionAction()
    {
        if ($this->getRequest()->isPost()) {
            $subregionId = $this->params()->fromPost('primaryId');
            $macroregionId = $this->params()->fromPost('secondaryId');

            $this->service->assignSubregionToMacroregion($subregionId, $macroregionId);

            $this->getResponse()->setContent('ok');
            return $this->getResponse();
        }

        $vm = new ViewModel(['form' => $this->subregionAssignForm]);
        $vm->setTerminal(true);
        return $vm;
    }

    public function unassignSubregionAction()
    {
        $subregionId = $this->params()->fromPost('id');
        $this->service->unassignSubregionFromMacroregion($subregionId);
        $this->getResponse()->setContent('ok');
        return $this->getResponse();
    }

    public function assignRegionAction()
    {
        if ($this->getRequest()->isPost()) {
            $regionId = $this->params()->fromPost('primaryId');
            $subregionId= $this->params()->fromPost('secondaryId');

            $this->service->assignRegionToSubregion($regionId, $subregionId);

            $this->getResponse()->setContent('ok');
            return $this->getResponse();
        }

        $vm = new ViewModel(['form' => $this->regionAssignForm]);
        $vm->setTerminal(true);
        return $vm;
    }

    public function unassignRegionAction()
    {
        $regionId = $this->params()->fromPost('id');
        $this->service->unassignRegionFromSubregion($regionId);
        $this->getResponse()->setContent('ok');
        return $this->getResponse();
    }
}