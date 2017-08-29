<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport\Amqp\Driver;

use EventBand\Transport\Amqp\Definition\ExchangeDefinition;
use EventBand\Transport\Amqp\Definition\QueueDefinition;

interface AmqpDriver
{
    public function publish(MessagePublication $publication, $exchange, $routingKey = '');

    public function consume($queue, callable $callback, $idleTimeout, $timeout = null);

    public function ack(MessageDelivery $delivery);

    public function reject(MessageDelivery $delivery);

    public function declareExchange(ExchangeDefinition $exchange);

    public function bindExchange($target, $source, $routingKey = '');

    public function declareQueue(QueueDefinition $queue);

    public function bindQueue($queue, $exchange, $routingKey = '');

    public function deleteExchange(ExchangeDefinition $exchange, $ifUnused = false, $nowait = false);

    public function deleteQueue(QueueDefinition $queue, $ifUnused = false, $ifEmpty = false, $nowait = false);
}
