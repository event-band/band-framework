<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace EventBand\Transport\Amqp;

use Che\LogStock\LoggerFactory;
use EventBand\Transport\Amqp\Driver\AmqpCluster;
use EventBand\Transport\Amqp\Driver\MessageConversionException;
use EventBand\Transport\Amqp\Driver\MessageEventConverter;
use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\Amqp\Driver\MessageDelivery;
use EventBand\Transport\EventCallbackException;
use EventBand\Transport\EventConsumer;
use EventBand\Transport\ReadEventException;

/**
 * Event consumer for AMQP Drivers
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class AmqpConsumer implements EventConsumer
{
    private $cluster;
    private $converter;
    private $queue;
    private $logger;

    /**
     * @param AmqpCluster           $cluster   Amqp cluster
     * @param MessageEventConverter $converter Convert amqp message to event
     * @param string                $queue     Queue name for consumption
     */
    public function __construct(AmqpCluster $cluster, MessageEventConverter $converter, $queue)
    {
        $this->cluster = $cluster;
        $this->converter = $converter;
        $this->queue = $queue;
        $this->logger = LoggerFactory::getLogger(__CLASS__);
    }

    /**
     * {@inheritDoc}
     */
    public function consumeEvents(callable $callback, $timeout)
    {
        $start = time();
        $drivers = $this->cluster->getDrivers($this->queue);
        if (empty($drivers)) {
            throw new ReadEventException("No available drivers in cluster");
        }
        /** @var AmqpDriver $driver */
        $driver = array_shift($drivers);

        $this->logger->debug('Consume events from queue', ['queue' => $this->queue, 'timeout' => $timeout]);

        try {
            $driver->consume($this->queue, $this->createDeliveryCallback($callback, $driver), $timeout);
        } catch (DriverException $e) {
            $driver->close();
            $this->logger->err("Driver error while consuming: $e");

            $elapsed = time() - $start;
            if ($timeout > 0) {
                $timeout -= $elapsed;
                if ($timeout === 0) {
                    $timeout = -1;
                }
            }

            if ($timeout < 0) {
                return;
            } else {
                $this->consumeEvents($callback, $timeout);
            }
        }
    }

    private function createDeliveryCallback(callable $callback, AmqpDriver $driver)
    {
        return function (MessageDelivery $delivery) use ($callback, $driver) {
            try {
                $this->logger->debug('Message delivery', ['delivery' => $delivery]);
                $event = $this->converter->messageToEvent($delivery->getMessage());
            } catch (MessageConversionException $e) {
                $this->logger->debug('Reject delivery on conversion error');
                $driver->reject($delivery);

                throw new ReadEventException('Error on event message conversion', $e);
            }

            try {
                $result = $callback($event);
            } catch (\Exception $e) {
                $this->logger->debug('Reject delivery on callback error');
                $driver->reject($delivery);

                throw new EventCallbackException($callback, $event, $e);
            }

            $this->logger->debug('Ack delivery');
            $driver->ack($delivery);

            return $result;
        };
    }
}
