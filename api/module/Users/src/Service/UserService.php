<?php

namespace Users\Service;

use Configuration\Entity\Position;
use Doctrine\ORM\EntityManager;
use Laminas\Crypt\Password\Bcrypt;
use Users\Entity\User;

class UserService
{
    private EntityManager $entityManager;

    private ?User $loggedUser = null;

    private ?array $cache = null;

    /**
     * UserService constructor.
     *
     * @param EntityManager $entityManager
     * @param null          $userId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function __construct(EntityManager $entityManager, $userId = null)
    {
        $this->entityManager = $entityManager;
        if ($userId) {
            $this->loggedUser = $this->entityManager->find(User::class, $userId);
        }
    }

    /**
     * Generates login url with provided token.
     *
     * @param string $token
     * @return string
     */
    public function generateLoginLink(string $token): string
    {
        return sprintf('%s://%s/remote-login/%s', $_SERVER['REQUEST_SCHEME'], $_SERVER['SERVER_NAME'], $token);
    }

    /**
     * @param User  $user
     * @param array $data
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user, array $data)
    {
        if (isset($data['user']['supervisor']) && !empty($data['user']['supervisor'])) {
            $supervisior = $this->entityManager->getRepository(User::class)->find($data['user']['supervisor']);
            $user->setSupervisor($supervisior);
        } else {
            $user->setSupervisor(null);
        }

        if (!empty($data['user']['configurationPosition'])) {
            $position = $this->entityManager->getRepository(Position::class)->find($data['user']['configurationPosition']);
            $user->setConfigurationPosition($position);
        }

        if (empty($data['user']['departments'])) {
            $user->getDepartments()->clear();
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param int or User $user
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function isLoggedUserSupervisorForUser($user)
    {
        if (is_int($user)) {
            $user = $this->entityManager->find(User::class, $user);
        }
        if ($this->loggedUser && $user instanceof User && $user->getSupervisor()) {
            return ($this->loggedUser->getId() === $user->getSupervisor()->getId()) ? true : false;
        }

        return false;
    }

    /**
     * Changes user password.
     *
     * @param User   $user
     * @param string $password
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePassword(User $user, string $password)
    {
        $user->setPassword(sha1($password));
        $bcrypt = new Bcrypt();
        $user->setPassword($bcrypt->create($password));
        $user->setLastPasswordChange(new \DateTime());
        $user->setTempPassword(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Updates user's last login date.
     *
     * @param int $userId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function updateLastLoginDate(int $userId)
    {
        $user = $this->entityManager->find(User::class, $userId);

        $user->setLastLogin(new \DateTime());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Checks wheather given user is subordinate of another user.
     *
     * @param int $subordinateId
     * @param int $userId
     * @return bool
     */
    public function isSubordinateOf(int $subordinateId, int $userId): bool
    {
        if (is_null($this->cache)) {
            $this->cache = $this->getSubordinatesArray($userId);
        }

        return in_array($subordinateId, $this->cache);
    }

    /**
     * Gets user's subordinates (all levels).
     *
     * @param int $userId
     * @return array|null
     */
    private function getSubordinatesArray(int $userId): array
    {
        $return = [$userId];

        $subordinates = $this->entityManager->getRepository(User::class)->getSubordinates($userId);
        foreach ($subordinates as $subordinate) {
            $ss = $this->getSubordinatesArray($subordinate->getId());

            if ($ss) {
                $return = array_merge($return, $ss);
            } else {
                $return[] = $subordinate->getId();
            }
        }

        return $return;
    }

    /**
     * @return User
     */
    public function getLoggedUser(): User
    {
        return $this->loggedUser;
    }

    /**
     * Updates user's process status.
     *
     * @param int $id
     * @param int $status
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function updateStatus(int $id, int $status): void
    {
        $user = $this->entityManager->find(User::class, $id);
        $user->setProcessStatus($status);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Generates random six digit login.
     *
     * @return int
     */
    private function generateLogin(): int
    {
        return random_int(100000, 999999);
    }

    /**
     * Generates random token.
     *
     * @return string
     */
    private function generateToken(): string
    {
        return sha1(str_shuffle(random_int(PHP_INT_MIN, PHP_INT_MAX) . microtime()));
    }
}