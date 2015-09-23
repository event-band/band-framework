<?php
/**
 * @LICENSE_TEXT
 */

namespace EventBand\Transport\Amqp\Pool\Strategy;

use EventBand\Transport\Amqp\Driver\AmqpDriver;
use EventBand\Transport\Amqp\Driver\DriverException;
use EventBand\Transport\Amqp\Pool\AmqpPool;
use EventBand\Transport\Amqp\Pool\AmqpPoolStrategy;

/**
 * Class RoundRobinStrategy
 * @package EventBand\Transport\Amqp\Pool\Strategy
 */
class RoundRobinStrategy implements AmqpPoolStrategy
{
    /**
     * @var AmqpPool
     */
    private $pool;

    /**
     * @var \SplObjectStorage
     */
    private $failed;

    /**
     * @param AmqpPool $pool
     */
    public function __construct(AmqpPool $pool)
    {
        $this->pool = $pool;
        $this->failed = new \SplObjectStorage();
    }

    /**
     * @return AmqpDriver
     */
    public function getDriver()
    {
        $drivers = $this->pool->getDrivers();

        shuffle($drivers);

        return current($drivers);
    }

    /**
     * @return bool
     */
    public function hasDriver()
    {
        return $this->pool->hasDriver();
    }

    /**
     * @param AmqpDriver $driver
     * @param \Exception $exception
     */
    public function onException(AmqpDriver $driver, \Exception $exception)
    {
        $this->failed->attach($driver, time());
    }
}
