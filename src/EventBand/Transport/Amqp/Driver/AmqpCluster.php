<?php

namespace EventBand\Transport\Amqp\Driver;

/**
 * Amqp cluster connection manager
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 */
class AmqpCluster
{
    /** @var AmqpDriver[] */
    private $drivers = [];
    private $queues = [];

    private $reloadTimeout;
    private $preConnect;

    /**
     * AmqpCluster constructor.
     *
     * @param AmqpDriver[]  $drivers
     * @param array         $queues
     * @param int           $reloadTimeout
     * @param int           $preConnect
     */
    public function __construct(array $drivers, array $queues = array(), $reloadTimeout = 0, $preConnect = 0)
    {
        $this->drivers = $drivers;
        $this->queues = $queues;
        $this->reloadTimeout = $reloadTimeout;
        $this->preConnect = $preConnect;

        $this->connectDrivers();
    }


    /**
     * @param String|null $queue
     *
     * @return AmqpDriver[]
     */
    public function getDrivers($queue = null) {
        $this->resetDrivers();
        $this->connectDrivers();

        $available = [];
        foreach ($this->drivers as $name => $driver) {
            if (!$driver->getClosed()) {
                if ($queue === null || !isset($this->queues[$queue]) || in_array($name, $this->queues[$queue])) {
                    $available[$name] = $driver;
                }
            }
        }

        return $available;
    }

    private function resetDrivers() {
        $now = time();
        foreach ($this->drivers as $driver) {
            if ($driver->getClosed() && $driver->getClosed() + $this->reloadTimeout <= $now) {
                $driver->reset();
            }
        }
    }

    private function connectDrivers() {
        if ($this->preConnect < 1) {
            return;
        }

        $connected  = 0;
        foreach ($this->drivers as $driver) {
            if (!$driver->getClosed()) {
                try {
                    $driver->connect();
                    $connected++;
                    if ($connected == $this->preConnect) {
                        return;
                    }
                } catch (\Exception $e) {
                    $driver->close();
                }
            }
        }
    }
}
