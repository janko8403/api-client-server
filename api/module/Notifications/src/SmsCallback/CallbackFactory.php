<?php


namespace Notifications\SmsCallback;


use Commissions\Service\GradeService;
use CommissionsManagement\Service\AnnexService;
use CommissionsManagement\Service\Commission50PctService;
use Doctrine\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Notifications\Entity\SmsLog;

class CallbackFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CallbackFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function factory(int $type): CallbackInterface
    {
        switch ($type) {
            case SmsLog::TYPE_COMMISSION_RATE:
                return new Rate($this->container->get(GradeService::class));
            case SmsLog::TYPE_PRESENCE_CONFIRMATION:
                return new PresenceConfirmation($this->container->get(ObjectManager::class));
            case SmsLog::TYPE_COMMISSION_TERMS_CHANGE:
                return new CommissionTermsChange(
                    $this->container->get(ObjectManager::class),
                    $this->container->get(AnnexService::class)
                );
            default:
                throw new \Exception("Unknown callback type `$type`");
        }
    }
}