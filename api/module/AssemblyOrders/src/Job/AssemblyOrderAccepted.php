<?php

namespace AssemblyOrders\Job;

use SlmQueue\Job\AbstractJob;

class AssemblyOrderAccepted extends AbstractJob
{
    public function execute()
    {
//        dump($this->getContent());
        throw new \Exception("Execution in SAP microservice");
    }
}