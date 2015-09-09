<?php

namespace EventBand\Transport\Amqp\Driver;

/**
 * Interface AmqpDriverReleasableInterface
 * @package EventBand\Transport\Amqp\Driver
 */
interface AmqpDriverReleasableInterface
{
    /**
     * @return void
     */
    public function releaseDriver();
}
