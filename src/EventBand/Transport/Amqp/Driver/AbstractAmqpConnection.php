<?php

namespace EventBand\Transport\Amqp\Driver;

use Che\LogStock\LoggerFactory;

/**
 * Class AbstractAmqpConnection
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 */
abstract class AbstractAmqpConnection implements AmqpDriverConnection
{
    protected $logger;

    protected $closed = 0;

    /**
     * AbstractAmqpConnection constructor.
     */
    public function __construct()
    {
        $this->logger = LoggerFactory::getLogger(get_class($this));
    }


    abstract public function doConnect();
    abstract public function doClose();
    abstract public function doReset();

    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        if (!$this->isConnected()) {
            $this->doConnect();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (!$this->closed) {
            try {
                $this->doClose();
            } catch (\Exception $e) {
                // ignore
                $this->logger->warn("Error while closing driver: $e");
            }
        }
        $this->closed = time();
    }

    /**
     * {@inheritdoc}
     */
    public function isReady()
    {
        return !$this->getClosed() && !$this->isConnected();
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        if ($this->isConnected()) {
            $this->close();
        }
        if ($this->getClosed()) {
            try {
                $this->doReset();
            } catch (\Exception $e) {
                $this->logger->warn("Error while resetting driver: $e");
            }

            $this->closed = 0;
        }
    }
}
