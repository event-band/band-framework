<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport\Amqp;

use EventBand\Transport\Amqp\Driver\MessageConversionException;
use EventBand\Transport\Amqp\Driver\MessageEventConverter;
use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\Amqp\Driver\MessageDelivery;
use EventBand\Transport\EventCallbackException;
use EventBand\Transport\EventConsumer;
use EventBand\Transport\ReadEventException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Event consumer for AMQP Drivers
 */
class AmqpConsumer implements EventConsumer
{
    private $driver;
    private $converter;
    private $queue;
    private $logger;

    /**
     * @param AmqpDriver            $driver    Driver for amqp
     * @param MessageEventConverter $converter Convert amqp message to event
     * @param string                $queue     Queue name for consumption
     */
    public function __construct(AmqpDriver $driver, MessageEventConverter $converter, $queue, LoggerInterface $logger = null)
    {
        $this->driver = $driver;
        $this->converter = $converter;
        $this->queue = $queue;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritDoc}
     */
    public function consumeEvents(callable $callback, $idleTimeout, $timeout = null)
    {
        try {
            $this->logger->debug(
                'Consume events from queue',
                ['queue' => $this->queue, 'idleTimeout' => $idleTimeout, 'maxExecutionTimeout' => $timeout]
            );
            $this->driver->consume($this->queue, $this->createDeliveryCallback($callback), $idleTimeout, $timeout);
        } catch (DriverException $e) {
            throw new ReadEventException('Driver error while consuming', $e);
        }
    }

    private function createDeliveryCallback(callable $callback)
    {
        return function (MessageDelivery $delivery) use ($callback) {
            try {
                $this->logger->debug('Message delivery', ['delivery' => $delivery]);
                $event = $this->converter->messageToEvent($delivery->getMessage());
            } catch (MessageConversionException $e) {
                $this->logger->debug('Reject delivery on conversion error');
                $this->driver->reject($delivery);

                throw new ReadEventException('Error on event message conversion', $e);
            }

            try {
                $result = $callback($event);
            } catch (\Exception $e) {
                $this->logger->debug('Reject delivery on callback error');
                $this->driver->reject($delivery);

                throw new EventCallbackException($callback, $event, $e);
            }

            $this->logger->debug('Ack delivery');
            $this->driver->ack($delivery);

            return $result;
        };
    }
}
