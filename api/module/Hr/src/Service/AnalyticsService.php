<?php


namespace Hr\Service;


use Commissions\Entity\CommissionUser;
use Doctrine\Persistence\ObjectManager;
use Rollbar\Rollbar;
use Hr\Entity\SubregionMacroregionJoint;
use Laminas\Http\Client;
use Laminas\Http\Exception\RuntimeException;

class AnalyticsService
{
    /**
     * @var string
     */
    private $ua;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * AnalyticsService constructor.
     *
     * @param ObjectManager $objectManager
     * @param string        $ua
     */
    public function __construct(ObjectManager $objectManager, string $ua)
    {
        $this->ua = $ua;
        $this->objectManager = $objectManager;
    }

    /**
     * Sends collect Google Analytics request.
     *
     * @param array $params
     */
    public function collect(array $params): void
    {
        $params = array_merge($params, [
            'v' => 1,
            'tid' => $this->ua,
        ]);
        $url = 'https://www.google-analytics.com/collect';
        $client = new Client($url);
        $client->setParameterGet($params);

        try {
            $client->send();
        } catch (RuntimeException $e) {
            Rollbar::error($e);
        }
    }

    /**
     * Processes accepting commission by a user.
     *
     * @param CommissionUser $cu
     */
    public function process(CommissionUser $cu): void
    {
        $region = $this->objectManager->getRepository(SubregionMacroregionJoint::class)
            ->getMacroregionForRegion($cu->getCommission()->getCreatingUser()->getRegion()->getId());

        $this->collect([
            't' => 'transaction',
            'cid' => $cu->getUser()->getAnalyticsUid(),
            'uid' => $cu->getUser()->getId(),
            'ti' => $cu->getId(),
            'tr' => $cu->getRate()->getMarginAmount() + $cu->getRate()->getTotalCostEmployee(), // $cu->getTotalRate(),
            'dh' => 'tikrow.com',
            'cs' => 'crm',
            'cm' => 'offline',
            'cd3' => $cu->getUser()->getId(),
            'cd6' => $cu->getCommission()->getStartDate()->format('Ymd'),
        ]);

        $this->collect([
            't' => 'item',
            'cid' => $cu->getUser()->getAnalyticsUid(),
            'uid' => $cu->getUser()->getId(),
            'ti' => $cu->getId(),
            'dh' => 'tikrow.com',
            'cs' => 'crm',
            'cm' => 'offline',
            'cd3' => $cu->getUser()->getId(),
            'in' => $region ? $region->getName() : 'brak',
            'iv' => $cu->getCommission()->getDefinition()->getName(),
            'ip' => $cu->getRate()->getMarginAmount() + $cu->getRate()->getTotalCostEmployee(),
            'iq' => 1,
            'cd6' => $cu->getCommission()->getStartDate()->format('Ymd'),
        ]);

        $this->collect([
            't' => 'event',
            'cid' => $cu->getUser()->getAnalyticsUid(),
            'uid' => $cu->getUser()->getId(),
            'dh' => 'tikrow.com',
            'cs' => 'crm',
            'cm' => 'offline',
            'cd3' => $cu->getUser()->getId(),
            'ec' => 'Wystawienie oceny powyżej 1',
            'ea' => $region ? $region->getName() : 'brak',
            'el' => $cu->getCommission()->getDefinition()->getName(),
            'cd6' => $cu->getCommission()->getStartDate()->format('Ymd'),
        ]);
    }
}