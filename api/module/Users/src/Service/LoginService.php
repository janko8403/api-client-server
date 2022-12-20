<?php


namespace Users\Service;


use Doctrine\Persistence\ObjectManager;
use Laminas\Http\Header\SetCookie;
use Laminas\Json\Json;
use Users\Entity\RemoteLogin;
use Users\Entity\User;

class LoginService
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var array
     */
    private $config;

    /**
     * LoginService constructor.
     *
     * @param ObjectManager $objectManager
     * @param array         $config
     */
    public function __construct(ObjectManager $objectManager, array $config)
    {
        $this->objectManager = $objectManager;
        $this->config = $config;
    }

    /**
     * Gets reemote login cookie.
     *
     * @param array $tokens
     * @param int   $userId
     * @return SetCookie
     */
    public function getCookie(array $tokens, int $userId): SetCookie
    {
        $user = $this->objectManager->find(User::class, $userId);

        return new SetCookie(
            'remote-token',
            base64_encode(Json::encode($tokens)),
            time() + 60 * 5,
            '/',
            $this->config['remote_login_path'],
        );
    }

    /**
     * Generates OAuth tokens (access and refresh).
     *
     * @param string $login
     * @return array
     */
    public function generateTokens(string $login): array
    {
        return $this->objectManager->getRepository(RemoteLogin::class)->createOAuthTokenPair($login);
    }

    /**
     * Gets redirect url for partner login.
     *
     * @param int $userId
     * @return string
     */
    public function getRedirectUrl(int $userId): string
    {
        $user = $this->objectManager->find(User::class, $userId);

        return $this->config['partner_domains'] . '/remote';
    }

    /**
     * Creates partner remote login request.
     *
     * @param int    $partnerId
     * @param int    $userId
     * @param string $reason
     * @return int
     */
    public function createRequest(int $partnerId, int $userId, string $reason): int
    {
        $login = new RemoteLogin();
        $login->setPartner($this->objectManager->find(User::class, $partnerId));
        $login->setCreatingUser($this->objectManager->find(User::class, $userId));
        $login->setExpirationDate(new \DateTime('+5 minutes'));
        $login->setReason($reason);
        $this->objectManager->persist($login);
        $this->objectManager->flush();

        return $login->getId();
    }

    /**
     * Gets login requests waiting for confirmation.
     *
     * @return array
     */
    public function getAllRequests(): array
    {
        return $this->objectManager->getRepository(RemoteLogin::class)->getAllRequests();
    }

    /**
     * Confirms login request.
     *
     * @param int $id
     * @param int $confirmingUserId
     */
    public function confirmRequest(int $id, int $confirmingUserId): void
    {
        $login = $this->getRequest($id);
        $login->setConfirmationDate(new \DateTime());
        $login->setConfirmingUser($this->objectManager->find(User::class, $confirmingUserId));
        $this->objectManager->persist($login);
        $this->objectManager->flush();
    }

    /**
     * Gets login request by id.
     *
     * @param int $id
     * @return RemoteLogin|null
     */
    public function getRequest(int $id): ?RemoteLogin
    {
        return $this->objectManager->find(RemoteLogin::class, $id);
    }

    /**
     * Marks login request as completed (user logged in).
     *
     * @param int $requestId
     */
    public function markLoginDate(int $requestId): void
    {
        $request = $this->getRequest($requestId);
        $request->setLoginDate(new \DateTime());
        $this->objectManager->persist($request);
        $this->objectManager->flush();
    }
}