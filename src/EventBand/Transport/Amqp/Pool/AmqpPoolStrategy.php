<?php
/**
 * @LICENSE_TEXT
 */

namespace EventBand\Transport\Amqp\Pool;

use EventBand\Transport\Amqp\Driver\AmqpDriver;

/**
 * Interface AmqpPoolStrategy
 * @package EventBand\Transport\Amqp\Pool
 */
interface AmqpPoolStrategy
{
    /**
     * @return AmqpDriver
     */
    public function getDriver();

    /**
     * @return bool
     */
    public function hasDriver();

    /**
     * @param AmqpDriver $driver
     * @param \Exception $exception
     */
    public function onException(AmqpDriver $driver, \Exception $exception);
}
