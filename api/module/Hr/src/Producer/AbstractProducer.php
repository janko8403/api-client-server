<?php


namespace Hr\Producer;


use Humus\Amqp\JsonProducer;

abstract class AbstractProducer
{
    /**
     * @var JsonProducer
     */
    protected $producer;

    /**
     * NotificationsProducer constructor.
     *
     * @param JsonProducer $producer
     */
    public function __construct(JsonProducer $producer)
    {
        $this->producer = $producer;
    }

    public function publish(array $params, string $routingKey)
    {
        $this->producer->publish($params, $routingKey);
    }
}