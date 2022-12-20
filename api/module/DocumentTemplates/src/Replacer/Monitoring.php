<?php

namespace DocumentTemplates\Replacer;

use Doctrine\Persistence\ObjectManager;
use Monitorings\Entity\MonitoringQuestionTemplateJoint;
use Monitorings\Entity\MonitoringUserFulfillment;
use Monitorings\Entity\MonitoringUserFulfillmentQuestionAnswer;

class Monitoring implements ReplacerInterface
{
    public function __construct(
        private ObjectManager $objectManager,
    )
    {
    }

    public function prepare(array $params): array
    {
        $from = $to = [];

        /** @var MonitoringUserFulfillment $fulfillment */
        $fulfillment = $params['fulfillment'];

        $answers = $this->objectManager->getRepository(MonitoringUserFulfillmentQuestionAnswer::class)
            ->findBy(['fulfillment' => $fulfillment]);

        /** @var MonitoringUserFulfillmentQuestionAnswer $answer */
        foreach ($answers as $answer) {
            /** @var MonitoringQuestionTemplateJoint $joint */
            $joint = $this->objectManager->getRepository(MonitoringQuestionTemplateJoint::class)
                ->findOneBy(['question' => $answer->getQuestion()]);
            $tag = $joint->getRewriteTag();
            if (!is_null($tag)) {
                $from[] = "[$tag]";
                $to[] = $answer->getValue();
            }
        }

        return [
            'from' => $from,
            'to' => $to,
        ];
    }
}