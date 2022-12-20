<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 23.05.2018
 * Time: 14:53
 */

namespace Users\Restriction;

use Commissions\Restriction\Commission12HWeekRestriction;
use Commissions\Restriction\Commission2HWorkDayRestriction;
use Commissions\Restriction\Commission35HWeekRestriction;
use Commissions\Restriction\Commission7hDayRestriction;
use Commissions\Restriction\CommissionMinimalWageRestriction;
use Commissions\Restriction\CommissionPayerHourLimit;
use Commissions\Restriction\CustomerBlockedUsers;
use Commissions\Restriction\PartnreDistance;
use Interop\Container\ContainerInterface;
use Hr\Setting\SystemSettingsService;
use Users\Entity\Restriction;

class RestrictionFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function factory(string $type)
    {
        switch ($type) {
            case Restriction::KEY_COMMISSION_7H_DAY:
                return new Commission7hDayRestriction();
            case Restriction::KEY_COMMISSION_12H_WEEK:
                return new Commission12HWeekRestriction();
            case Restriction::KEY_COMMISSION_35H_WEEK:
                return new Commission35HWeekRestriction();
            case Restriction::KEY_COMMISSION_MINIMAL_WAGE:
                return new CommissionMinimalWageRestriction();
            case Restriction::KEY_COMMISSION_2H_WORK_DAY:
                return new Commission2HWorkDayRestriction($this->container->get(SystemSettingsService::class));
            case Restriction::KEY_COMMISSION_PAYER_HOUR_LIMIT:
                return new CommissionPayerHourLimit();
            case Restriction::KEY_CUSTOMER_BLOCKED_USERS:
                return new CustomerBlockedUsers();
            case Restriction::KEY_PARTNER_DISTANCE:
                return new PartnreDistance();
            default:
                throw new \Exception("Unknown restriction type `$type`");
        }
    }
}