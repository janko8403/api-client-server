# Komfort App

Readme provides informations necessary to properly configure the application.

## RabbitMQ configuration

Steps to execute to properly configure RabbitMQ instance (using RabbitMQ admin).

### Queues

Add new queues (*classic, durable, Dead Letter Exchange=<queue_name>*) with names:

1. assembly-order-accepted

### Exchanges

Add new exchanges (*direct, durable*) with names:

1. assembly-order-accepted

**Bind each of the newly created exchanges to a corresponding queue.**

## RabbitMQ workers

Workers can be started from command line using commands:

``<project-dir>/vendor/bin/laminas slm-queue:start <queue-name>``

Workers in production should be run under watchdog type application.
Repository provides configuration file for [PM2](https://pm2.keymetrics.io/). Start using ``pm2 start`` command.

## RabbitMQ integration

Other systems pushing messages to RabbitMQ queue must conform to specific message format:

``{"content":"a:3:{s:2:\"id\";i:1;s:7:\"user_id\";i:2;s:4:\"date\";i:1657638718;}","metadata":{"__name__":"AssemblyOrders\\Job\\AssemblyOrderAccepted"}}
``

Message is JSON serialized with *content* key containing data serialized with PHP ``serialize()`` function.
*Metadata* key should contain *\_\_name\_\_* property with one of the following class names (each class name corresponds
to a
specific queue):

1. AssemblyOrders\Job\AssemblyOrderAccepted (*assembly-order-accepted*)
