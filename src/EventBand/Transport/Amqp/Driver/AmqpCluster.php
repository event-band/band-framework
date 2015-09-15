<?php

namespace EventBand\Transport\Amqp\Driver;

/**
 * Class AmqpDriverCluster
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 */
class AmqpCluster
{
    /** @var AmqpDriver[] */
    private $drivers = [];
    private $queues = [];

    private $reloadTimeout;

    /**
     * AmqpCluster constructor.
     *
     * @param AmqpDriver[]  $drivers
     * @param array         $queues
     * @param int           $reloadTimeout
     */
    public function __construct(array $drivers, array $queues = array(), $reloadTimeout = 0)
    {
        $this->drivers = $drivers;
        $this->queues = $queues;
        $this->reloadTimeout = $reloadTimeout;
    }


    /**
     * @param String|null $queue
     *
     * @return AmqpDriver[]
     */
    public function getDrivers($queue = null) {
        $this->resetDrivers();

        $available = [];
        foreach ($this->drivers as $name => $driver) {
            if (!$driver->getClosed()) {
                if ($queue === null || !isset($this->queues[$queue]) || in_array($name, $this->queues[$queue])) {
                    $available[] = $driver;
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
}
