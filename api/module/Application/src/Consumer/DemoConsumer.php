<?php


namespace Application\Consumer;


class DemoConsumer
{
    public function __invoke(\Humus\Amqp\Envelope $envelope, \Humus\Amqp\Queue $queue)
    {
        echo $envelope->getBody();
        return \Humus\Amqp\DeliveryResult::MSG_ACK();

    }
}