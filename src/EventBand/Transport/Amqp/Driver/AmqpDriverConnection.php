<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace EventBand\Transport\Amqp\Driver;

use EventBand\Transport\Amqp\Definition\ExchangeDefinition;
use EventBand\Transport\Amqp\Definition\QueueDefinition;

/**
 * Description of AmqpDriverConnection
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
interface AmqpDriverConnection
{
    /**
     * Connect. Should do nothing if connection is already connected
     *
     * @return void
     * @throws DriverException
     */
    public function connect();

    /**
     * Check if connection is connected
     *
     * @return bool
     */
    public function isConnected();

    /**
     * Close connection
     *
     * @return void
     */
    public function close();

    /**
     * Get close time
     *
     * @return int Close time, 0 if connection was not closed
     */
    public function getClosed();

    /**
     * Check if connection is ready to be connected
     *
     * @return bool
     */
    public function isReady();

    /**
     * Reset connection to ready state
     *
     * @return void
     */
    public function reset();
}
