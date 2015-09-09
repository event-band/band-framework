<?php

namespace EventBand\Transport\Amqp\Driver;

/**
 * Interface AmqpDriverPoolInterface
 * @package EventBand\Transport\Amqp\Driver
 */
interface AmqpDriverPoolInterface
{
    /**
     * @param \EventBand\Transport\Amqp\Driver\AmqpDriver $driver
     */
    public function addDriver(AmqpDriver $driver);

    /**
     * @return \EventBand\Transport\Amqp\Driver\AmqpDriver
     */
    public function getDriver();
}
