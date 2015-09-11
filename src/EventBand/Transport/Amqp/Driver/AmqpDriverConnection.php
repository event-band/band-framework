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
     * @return bool
     */
    public function connect();

    /**
     * @return bool
     */
    public function isConnected();

    /**
     * @return bool
     */
    public function close();

    /**
     * @return bool
     */
    public function isClosed();

    /**
     * @return bool
     */
    public function reset();
}
