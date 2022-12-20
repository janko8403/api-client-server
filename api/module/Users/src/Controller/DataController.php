<?php

namespace Users\Controller;

use Commissions\Service\CommisionService;
use Commissions\Service\CommissionFetchService;
use Doctrine\Persistence\ObjectManager;
use Hr\Controller\BaseController;
use Hr\File\FileService;
use Laminas\View\Model\ViewModel;
use Notifications\Entity\Notification;
use Users\Entity\BlockedData;
use Users\Entity\User;
use Users\Entity\UserData;
use Users\Entity\UserDataDetail;
use Users\Service\BlockedDataService;
use Users\Service\UserDataService;

class DataController extends BaseController
{
    protected ObjectManager $objectManager;

    private UserDataService $userDataService;

    private FileService $fileService;

    private BlockedDataService $blockedDataService;

    private CommissionFetchService $fetchService;

    /**
     * DataController constructor.
     *
     * @param ObjectManager          $objectManager
     * @param UserDataService        $userDataService
     * @param FileService            $fileService
     * @param BlockedDataService     $blockedDataService
     * @param CommissionFetchService $fetchService
     */
    public function __construct(
        ObjectManager          $objectManager,
        UserDataService        $userDataService,
        FileService            $fileService,
        BlockedDataService     $blockedDataService,
        CommissionFetchService $fetchService
    )
    {
        $this->objectManager = $objectManager;
        $this->userDataService = $userDataService;
        $this->fileService = $fileService;
        $this->blockedDataService = $blockedDataService;
        $this->fetchService = $fetchService;
    }

    public function indexAction()
    {
        $userId = $this->params('id');
        $user = $this->objectManager->find(User::class, $userId);

        $bcstring = $this->userDataService->buildBreadcrumb($userId);
        $this->addBreadcrumbsPart($bcstring);
        $vm = new ViewModel();
        $userData = $this->userDataService->userDataArray();
        $oldData = $this->userDataService->getLastInactiveData($userId);
        $actualData = $this->userDataService->getActiveData($userId);

        $pesel = $this->userDataService->fetchActiveDataWithKeysOrId($userId)[UserData::KEY_PESEL] ?? null;
        if ($pesel) {
            $vm->setVariable('blocked', $this->blockedDataService->isBlocked(BlockedData::TYPE_PESEL, $pesel));
        }

        $futureCommissions = $this->fetchService
            ->getPartnerCommissions($user, CommisionService::STATE_PLANNED)->execute();
        $vm->setVariable('futureCommissions', count($futureCommissions) > 0);

        // messages to partner
        $notifications = $this->objectManager->getRepository(Notification::class)->findBy([
            'type' => Notification::TYPE_USER_DATA_VERSION_INFO,
        ], ['subject' => 'asc']);

        $vm->setVariables([
            'userData' => $userData,
            'userId' => $userId,
            'oldData' => $oldData,
            'actualData' => $actualData,
            'objectManager' => $this->objectManager,
            'fileService' => $this->fileService,
            'notifications' => $notifications,
            'comment' => (isset($actualData[0]) && !empty($actualData[0]->getVersion()->getComment())) ? $actualData[0]->getVersion()->getComment() : null,
        ]);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            if ($this->userDataService->save($data, $userId)) {
                $this->flashMessenger()->addSuccessMessage('Rekord został zaktualizowany.');
            } else {
                $this->flashMessenger()->addSuccessMessage('Dane rekordu nie zostały zmienione');
            }
            $this->redirect()->toRoute('users');
        }

        return $vm;
    }

    public function downloadAction()
    {
        $field = $this->params('field');
        $userId = $this->params('id');
        $path = $this->fileService->getPath(FileService::TYPE_QUESTIONNAIRE_ATTACHMENTS, ['USER_ID' => $userId]);

        $file = $this->objectManager->getRepository(UserDataDetail::class)->getActiveValueForKey($userId, $field);
        if (isset($file[0]) && $file[0]->getValue()) {
            return $this->fileService->serveFile(
                $path . '/' . $file[0]->getValue(),
                sprintf('%s_%d', $field, $userId)
            );
        } else {
            $this->redirect()->toRoute('users');
        }
    }
}
