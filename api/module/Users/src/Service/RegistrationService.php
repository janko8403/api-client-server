<?php
/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 12.10.17
 * Time: 11:50
 */

namespace Users\Service;

use Commissions\Service\CommisionService;
use Commissions\Service\CommissionFetchService;
use Configuration\Entity\Position;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ObjectManager;
use Features\Entity\Event;
use Features\Entity\Feature;
use Features\Service\FeatureService;
use Hr\Service\PostCodeService;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\EventManager\EventManager;
use Laminas\Filter\Digits;
use Laminas\Filter\StringTrim;
use Laminas\I18n\Validator\PhoneNumber;
use Laminas\Mvc\I18n\Translator;
use Notifications\Entity\Notification;
use Notifications\Sms\SmsService;
use Rollbar\Rollbar;
use SMSApi\Exception\SmsapiException;
use Users\Entity\PasswordToken;
use Users\Entity\User;
use Users\Entity\UserData;
use Users\Entity\UserDataDetail;
use Users\Entity\UserDataVersion;
use Users\Notification\PasswordResetNotification;

class RegistrationService
{
    const RESET_PASSWORD_COUNT = 5;
    const RESET_PASSWORD_CODE_LENGTH = 4;
    const RESET_PASSWORD_ATTEMPTS = 3;

    protected ObjectManager $objectManager;

    private EventManager $eventManager;

    private Translator $translator;

    private SmsService $smsService;

    private PostCodeService $postCodeService;

    /**
     * RegistrationService constructor.
     *
     * @param ObjectManager   $objectManager
     * @param EventManager    $eventManager
     * @param Translator      $translator
     * @param SmsService      $smsService
     * @param PostCodeService $postCodeService
     */
    public function __construct(
        ObjectManager   $objectManager,
        EventManager    $eventManager,
        Translator      $translator,
        SmsService      $smsService,
        PostCodeService $postCodeService,
    )
    {
        $this->objectManager = $objectManager;
        $this->eventManager = $eventManager;
        $this->translator = $translator;
        $this->smsService = $smsService;
        $this->postCodeService = $postCodeService;
    }

    /**
     * @param $data
     * @return int
     * @throws \ReflectionException|\Exception
     */
    public function tikrowRegister($data): int
    {
        $user = new User();
        $position = $this->objectManager->getRepository(Position::class)->findOneBy(['key' => Position::POSITION_TIKROW_PARTNER]);
        $filter = new Digits();
        $trimFilter = new StringTrim();
        $login = $filter->filter($data['registrationForm']['login']);
        $login = $trimFilter->filter($login);
        $password = $this->rand_passwd(6, false);

        $user->setReferer($data['registrationForm']['referer'] ?? '');
        $user->setPassword(sha1($password));
        $user->setPasswordBC((new Bcrypt())->create($password));
        $user->setLogin($login);
        $user->setPhonenumber($login);
        $user->setName('');
        $user->setSurname('');
        $user->setEmail('');
        $user->setPostCode($data['registrationForm']['post_code']);
        $user->setOnlyS3(true);
        $user->setConfigurationPosition($position);
        $user->setStatus(User::TR_STATUS_INITIAL);
        $user->setCreationDate(new \DateTime());
        $user->setNoTaxStatement(true);
        $user->setCategory(User::CATEGORY_STUDENT);

        if (!empty($data['registrationForm']['affiliateCode'])) {
            $affiliatedUser = $this->objectManager->getRepository(User::class)
                ->findOneBy(['affiliateCode' => $data['registrationForm']['affiliateCode']]);

            if ($affiliatedUser) {
                $user->setAffiliatedUser($affiliatedUser);
            }
        }

        if (!empty($data['registrationForm']['campaign'])) {
            $user->setCampaign(base64_decode($data['registrationForm']['campaign']));
        }

        if ($invite = ($data['registrationForm']['invite'] ?? null)) {
            $invite = $this->objectManager->getRepository(Invite::class)->findOneBy(['token' => $invite]);
            if ($invite) {
                $user->setInvite($invite);
                $invite->setUser($user);
                $this->objectManager->persist($invite);
            }
        }

        // process post code
        $code = $this->postCodeService->findCode($user->getPostCode());
        if (!$code) {
            $code = $this->postCodeService->createCode($user->getPostCode());
        }
        $user->setLat($code->getLat());
        $user->setLng($code->getLng());

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        // find lowest visibility distance
        if ($user->getLat() && $user->getLng()) {
            $user->setDistance(5);
            $this->findLowestDistance($user);
        }

        // set features
        $this->featureService->setValue(Feature::KEY_LUMP_SUM, 0, $user->getId(), $user->getId(), Event::TYPE_REGISTRATION);
        $this->featureService->setValue(Feature::KEY_PPK, 0, $user->getId(), $user->getId(), Event::TYPE_REGISTRATION);
        $this->featureService->setValue(Feature::KEY_WORK_FUND, 0, $user->getId(), $user->getId(), Event::TYPE_REGISTRATION);
        $this->featureService->setValue(Feature::KEY_YOUTH_TAX, 1, $user->getId(), $user->getId(), Event::TYPE_REGISTRATION);
        $this->featureService->setValue(Feature::KEY_EMPLOYMENT_STATUS, User::CATEGORY_STUDENT, $user->getId(), $user->getId(), Event::TYPE_REGISTRATION);

        $this->addTermsAndConditionsAcceptance($user);
        $this->addUserData($user);

        $this->sendSms($password, $user);
        $this->sendSmsInfo($user);

//        $this->eventManager->trigger(
//            Notification::EVENT_SEND_NOTIFICATION,
//            RegistrationNotification::class,
//            ['user' => $user]
//        );

        return $user->getId();
    }

    public function rand_passwd($length = 8, $bcrypt = true): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $rand = rand(0, strlen($chars) - 1);
            $password .= substr($chars, $rand, 1);
        }

        if ($bcrypt) {
            $password = (new Bcrypt())->create($password);
        }

        return $password;
    }

    private function findLowestDistance(User $user)
    {
        // distance 5 set initially
        $distances = [10, 20, 50, 100];

        $commissions = $this->fetchService->getCommissions($user, ['state' => CommisionService::STATE_AVAILABLE]);
        while (count($commissions) == 0 && count($distances) > 0) {
            $distance = array_shift($distances);
            $user->setDistance($distance);
            $this->objectManager->persist($user);
            $this->objectManager->flush();
            $commissions = $this->fetchService->getCommissions($user, ['state' => CommisionService::STATE_AVAILABLE]);
        }

        if (count($commissions) == 0 && count($distances) == 0) {
            $user->setDistance(null);
            $this->objectManager->persist($user);
            $this->objectManager->flush();
        }
    }

    /**
     * @param User $user
     * @throws \Exception
     */
    private function addTermsAndConditionsAcceptance(User $user): void
    {
        $regulations = $this->objectManager->getRepository(Agreement::class)->findOneBy([
            'key' => Agreement::AGREEMENT_REGULATIONS,
            'isActive' => 1,
        ]);

        $userAgreement = new UserAgreement();
        $userAgreement->setAcceptDate(new \DateTime());
        $userAgreement->setAgreement($regulations);
        $userAgreement->setUser($user);

        $this->objectManager->persist($userAgreement);
        $this->objectManager->flush();
    }

    /**
     * add default user data and default tax rate 0.54
     *
     * @param User $user
     */
    private function addUserData(User $user): void
    {
        $newVersion = new UserDataVersion();
        $newVersion->setIsActive(true);
        $newVersion->setCreatingUser($user);
        $newVersion->setUser($user);
        $newVersion->setCreationDate(new \DateTime());
        $this->objectManager->persist($newVersion);
        $this->objectManager->flush();

        $keys = UserData::getUserDataKeysArray();
        $reflection = new \ReflectionObject($user);

        foreach ($keys as $key) {
            $userData = $this->objectManager->getRepository(UserData::class)->findOneBy(['key' => $key]);

            if ($userData && $reflection->hasProperty($key)) {
                $prop = $reflection->getProperty($key);
                $prop->setAccessible(true);
                $newUserDataDetail = new UserDataDetail();
                $newUserDataDetail->setValue($prop->getValue($user));
                $newUserDataDetail->setVersion($newVersion);
                $newUserDataDetail->setData($userData);
                $this->objectManager->persist($newUserDataDetail);
            }
        }

        $taxDetail = $this->objectManager->getRepository(Userdata::class)->findOneBy(['key' => UserData::KEY_TAX_RATE]);
        if ($taxDetail) {
            $newUserDataDetail = new UserDataDetail();
            $newUserDataDetail->setValue(UserDataDetail::DEFAULT_TAX_RATE_VALUE);
            $newUserDataDetail->setVersion($newVersion);
            $newUserDataDetail->setData($taxDetail);
            $this->objectManager->persist($newUserDataDetail);
        }

        $userCategory = $this->objectManager->getRepository(Userdata::class)->findOneBy(['key' => UserData::KEY_CATEGORY]);
        if ($userCategory) {
            $newUserDataDetail2 = new UserDataDetail();
            $newUserDataDetail2->setValue(User::CATEGORY_STUDENT);
            $newUserDataDetail2->setVersion($newVersion);
            $newUserDataDetail2->setData($userCategory);
            $this->objectManager->persist($newUserDataDetail2);
        }

        $this->objectManager->flush();
    }

    private function sendSms(string $password, User $user): void
    {
        $notification = $this->objectManager->getRepository(Notification::class)
            ->findOneBy(['type' => Notification::TYPE_SMS_REGISTRATION]);
        $message = str_replace('[haslo]', $password, $notification->getContent());

        try {
            $this->smsService->send($user->getPhonenumber(), $message);
        } catch (SmsapiException $exception) {
            Rollbar::error($exception);
        }
    }

    private function sendSmsInfo(User $user)
    {
        try {
            $msg = $this->smsService->getContent(
                Notification::TYPE_SMS_REGISTRATION_INFO
            );
            $this->smsService->send($user->getPhonenumber(), $msg);
        } catch (SmsapiException $exception) {
            Rollbar::error($exception);
        }
    }

    public function sendResetPasswordCode($data): array
    {
        /** @var User $user */
        $user = $this->objectManager->getRepository(User::class)->findOneBy(['login' => $data['login']['login'], 'isactive' => true]);
        if ($user) {
            $passwordTokens = $this->objectManager->getRepository(PasswordToken::class)->getCodesFromLastNMinutes($user->getId(), 5);
            if ($passwordTokens && count($passwordTokens) >= self::RESET_PASSWORD_COUNT) {
                $message = $this->translator->translate("Zbyt duzo prób resetowania hasła. Prosimy spróbować później.");

                return [false, $message];
            }

            if ($data['messageType'] == 'sms') {
                $this->sendResetPasswordCodeBySms($user);
            } elseif ($data['messageType'] == 'email') {
                $this->sendResetPasswordCodeByEmail($user);
            }
        }

        return [
            true,
            $this->translator->translate('Kod resetowania hasła został wysłany. W razie problemów prosimy o kontakt z Biurem Obsługi'),
        ];
    }

    private function sendResetPasswordCodeBySms(User $user): void
    {
        $validator = new PhoneNumber([
            'allowedTypes' => [
                'general',
                'personal',
                'mobile',
            ],
            'country' => 'PL',
        ]);
        $phoneNumer = $validator->isValid($user->getLogin()) ? $user->getLogin() : $user->getPhonenumber();

        if ($validator->isValid($phoneNumer)) {
            $code = $this->getResetCode($user);
            $this->sendSmsByPhoneNumber($phoneNumer, $code);
        }
    }

    private function getResetCode(User $user): string
    {
        $token = new PasswordToken();
        $token->setUser($user);
        while (true) {
            try {
                $code = $this->generateResetPasswordCode();

                $token->setCode($code);
                $this->objectManager->persist($token);
                $this->objectManager->flush();
                break;
            } catch (UniqueConstraintViolationException $e) {
                continue;
            }
        }

        return $code;
    }

    private function generateResetPasswordCode(): string
    {
        $result = '';
        $marks = array_merge(range('a', 'z'), range(0, 9));
        for ($i = 0; $i < self::RESET_PASSWORD_CODE_LENGTH; $i++) {
            $result .= $marks[mt_rand(0, count($marks))];
        }

        return $result;
    }

    public function sendSmsByPhoneNumber($number, $message, $sender = "Nadawca")
    {
        try {
            $this->smsService->send($number, $message, $sender);
        } catch (SmsapiException $exception) {
            Rollbar::error($exception);
        }
    }

    private function sendResetPasswordCodeByEmail(User $user): void
    {
        $code = $this->getResetCode($user);

        $this->eventManager->trigger(
            Notification::EVENT_SEND_NOTIFICATION,
            PasswordResetNotification::class,
            ['user' => $user, 'code' => $code]
        );
    }

    public function saveNewPassword($data): void
    {
        $passwordToken = $this->objectManager->getRepository(PasswordToken::class)->findOneBy(['code' => $data['code']]);

        if ($passwordToken) {
            /** @var User $user */
            $user = $passwordToken->getUser();

            $user->setPassword(sha1($data['password']));
            $bcrypt = new Bcrypt();
            $user->setPasswordBC($bcrypt->create($data['password']));
            $user->setLastPasswordChange(new \DateTime());

            $passwordToken->setIsactive(false);

            $this->objectManager->persist($passwordToken);
            $this->objectManager->persist($user);
            $this->objectManager->flush();
        }
    }
}