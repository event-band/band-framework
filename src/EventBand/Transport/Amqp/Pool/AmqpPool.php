<?php

namespace EventBand\Transport\Amqp\Pool;

use EventBand\Transport\Amqp\Definition\ExchangeDefinition;
use EventBand\Transport\Amqp\Definition\QueueDefinition;
use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\AmqpDriverPoolInterface;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\Amqp\Driver\MessageDelivery;
use EventBand\Transport\Amqp\Driver\MessagePublication;

/**
 * Class AmqpPool
 * @package EventBand\Transport\AmqpLib\Pool
 */
class AmqpPool
{
    /**
     * @var AmqpDriver[]
     */
    private $drivers = [];

    /**
     * @param AmqpDriver $driver
     */
    public function addDriver(AmqpDriver $driver)
    {
        $this->drivers[] = $driver;
    }

    /**
     * @return AmqpDriver[]
     */
    public function getDrivers()
    {
        return array_filter($this->drivers, function (AmqpDriver $driver) {
            return !$driver->isClosed();
        });
    }

    /**
     * @return bool
     */
    public function hasDriver()
    {
        foreach ($this->drivers as $driver) {
            if (!$driver->isClosed()) {
                return true;
            }
        }

        return false;
    }
}
