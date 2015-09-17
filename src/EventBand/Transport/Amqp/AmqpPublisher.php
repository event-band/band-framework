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
use EventBand\Event;
use EventBand\Routing\EventRouter;
use EventBand\Transport\Amqp\Driver\AmqpCluster;
use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\Amqp\Driver\EventConversionException;
use EventBand\Transport\Amqp\Driver\MessageEventConverter;
use EventBand\Transport\Amqp\Driver\MessagePublication;
use EventBand\Transport\Balancer\BalancingPolicy;
use EventBand\Transport\EventPublisher;
use EventBand\Transport\PublishEventException;

/**
 * Event publisher for AMQP drivers
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class AmqpPublisher implements EventPublisher
{
    private $cluster;
    private $driver;
    private $converter;
    private $exchange;
    private $router;
    private $balancer;
    private $persistent;
    private $mandatory;
    private $immediate;
    private $logger;

    /**
     * @param AmqpCluster           $cluster    Driver for amqp
     * @param MessageEventConverter $converter  Event will be converted to message
     * @param string                $exchange   Name of exchange
     * @param BalancingPolicy|null  $balancer   Optional connection balancer
     * @param EventRouter|null      $router     If not null routing key will be generate with router
     * @param bool                  $persistent Message will be persistent or not
     * @param bool                  $mandatory  Check if message is routed to queues
     * @param bool                  $immediate  Message should be consumed immediately
     */
    public function __construct(AmqpCluster $cluster, MessageEventConverter $converter, $exchange,
                                EventRouter $router = null, BalancingPolicy $balancer = null,
                                $persistent = true, $mandatory = false, $immediate = false)
    {
        $this->cluster = $cluster;
        $this->converter = $converter;
        $this->exchange = $exchange;
        $this->router = $router;
        $this->balancer = $balancer;
        $this->persistent = $persistent;
        $this->mandatory = $mandatory;
        $this->immediate = $immediate;
        $this->logger = LoggerFactory::getLogger(get_class($this));
    }

    /**
     * {@inheritDoc}
     */
    public function publishEvent(Event $event)
    {
        try {
            $pub = new MessagePublication(
                $this->converter->eventToMessage($event),
                $this->persistent,
                $this->mandatory,
                $this->immediate
            );

            $routingKey = $this->getEventRoutingKey($event);
            $this->logger->debug('Publish message to exchange', [
                'publication' => $pub,
                'exchange' => $this->exchange,
                'routingKey' => $routingKey
            ]);

            $drivers = $this->cluster->getDrivers();
            if ($this->balancer) {
                $drivers = $this->balancer->getConnections($event, $drivers);
            }

            if (empty($drivers)) {
                throw new DriverException("No drivers available");
            }

            $this->doPublish($pub, $routingKey, $drivers);

        } catch (EventConversionException $e) {
            throw new PublishEventException($event, 'Event to message conversion error', $e);
        } catch (DriverException $e) {
            throw new PublishEventException($event, 'Message publish error', $e);
        }
    }

    /**
     * @param MessagePublication $pub
     * @param string             $routingKey
     * @param AmqpDriver[]       $drivers
     * @param int                $attempt
     */
    private function doPublish(MessagePublication $pub, $routingKey, array $drivers, $attempt = 1) {
        /** @var AmqpDriver $driver */
        $driver = array_shift($drivers);
        try {
            $driver->publish($pub, $this->exchange, $routingKey);
        } catch (DriverException $e) {
            if (!empty($drivers)) {
                $this->logger->err("Driver error while publishing on attempt #$attempt: $e");
                $this->doPublish($pub, $routingKey, $drivers, $attempt + 1);
            } else {
                throw $e;
            }
        }
    }

    private function getEventRoutingKey(Event $event)
    {
        return $this->router ? $this->router->routeEvent($event) : '';
    }
}
