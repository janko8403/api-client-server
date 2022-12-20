<?php


namespace DocumentTemplates\Replacer;


use Users\Entity\UserData as UserDataDic;
use Users\Service\UserDataService;

class UserData implements ReplacerInterface
{
    /**
     * @var UserDataService
     */
    private $userDataService;

    /**
     * UserData constructor.
     * @param UserDataService $userDataService
     */
    public function __construct(UserDataService $userDataService)
    {
        $this->userDataService = $userDataService;
    }

    public function prepare(array $params): array
    {
        if (empty($params['userId'])) {
            throw new \Exception("Parameter userId not provided");
        }

        $from = [];
        $to = [];
        $activeUserData = $this->userDataService->fetchActiveDataWithKeys($params['userId']);

        if (!empty($activeUserData)) {
            foreach ($activeUserData as $key => $value) {
                $from[] = '[' . $key . ']';
                $to[] = $value;
            }
        }

        if (!in_array('[' . UserDataDic::KEY_BANK_ACCOUNT_NO . ']', $from)) {
            $from[] = '[' . UserDataDic::KEY_BANK_ACCOUNT_NO . ']';
            $to[] = '';
        }

        if (!in_array('[' . UserDataDic::KEY_BANK_ING_ACCOUNT_NO . ']', $from)) {
            $from[] = '[' . UserDataDic::KEY_BANK_ING_ACCOUNT_NO . ']';
            $to[] = '';
        }

        return compact('from', 'to');
    }

}