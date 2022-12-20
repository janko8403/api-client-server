<?php

namespace NpsRating\Controller;

use NpsRating\Service\NpsRatingClearService;
use NpsRating\Service\NpsRatingAddCsvService;
use NpsRating\Form\NpsRatingForm;
use Hr\Controller\BaseController;

class IndexController extends BaseController
{
    public function __construct(private NpsRatingClearService $clear, private NpsRatingAddCsvService $addCsvService) 
    {
    }

    public function indexAction()
    {
        $form = new NpsRatingForm('upload-form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $this->addCsvService->getCsv($post);
            if(!empty($this->addCsvService->getMessage()))
                $this->flashMessenger()->addErrorMessage($this->addCsvService->getMessage());
            else 
                $this->flashMessenger()->addSuccessMessage('Dane zostały poprawnie dodane.');

            return $this->redirect()->toRoute('nps-ratings');
        }

        return ['form' => $form];
    }

    public function clearAction()
    {
        $this->clear->clearAll();
        $this->flashMessenger()->addSuccessMessage('Dane zostały poprawnie usunięte.');
        return $this->redirect()->toRoute('nps-ratings');
    }
}