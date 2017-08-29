<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Processor;

use EventBand\Event;
use EventBand\BandDispatcher;
use EventBand\Transport\EventConsumer;

/**
 * Process consumed events through dispatcher
 */
class DispatchProcessor
{
    private $dispatcher;
    private $consumer;
    private $timeout;
    private $band;
    /**
     * @var int
     */
    private $idleTimeout;

    /**
     * @param BandDispatcher $dispatcher  Dispatcher
     * @param EventConsumer  $consumer    Consumer
     * @param string         $band        Name of band for dispatcher
     * @param int            $idleTimeout Timeout in second for consumer
     */
    public function __construct(BandDispatcher $dispatcher, EventConsumer $consumer, $band, $idleTimeout, $timeout = null)
    {
        $this->dispatcher = $dispatcher;
        $this->consumer = $consumer;

        $band = (string) $band;
        if (empty($band)) {
            throw new \InvalidArgumentException('Band should not be empty');
        }
        $this->band = $band;

        $this->timeout = $timeout;
        $this->idleTimeout = $idleTimeout;
    }

    /**
     * Process events
     */
    public function process()
    {
        $dispatching = true;

        $this->dispatcher->dispatchEvent(new ProcessStartEvent());

        $dispatchCallback = $this->getDispatchCallback($dispatching);

        while ($dispatching) {
            $this->consumer->consumeEvents($dispatchCallback, $this->idleTimeout, $this->timeout);
            if ($dispatching) { // stopped by timeout
                $dispatchTimeout = new DispatchTimeoutEvent($this->idleTimeout ?: $this->timeout);
                $this->dispatcher->dispatchEvent($dispatchTimeout);

                $dispatching = $dispatchTimeout->isDispatching();
            }
        }

        $this->dispatcher->dispatchEvent(new ProcessStopEvent());
    }

    /**
     * @deprecated timeout property overriding does not guarantee real timeout changing
     * @param $timeout
     */
    public function setTimeout($timeout)
    {
        if (($timeout = (int)$timeout) < 0) {
            throw new \InvalidArgumentException(sprintf('Timeout %d < 0', $timeout));
        }
        $this->timeout = $timeout;
    }

    private function getDispatchCallback(&$dispatching)
    {
        return function (Event $event) use (&$dispatching) {
            $dispatchStart = new DispatchStartEvent($event);
            $this->dispatcher->dispatchEvent($dispatchStart);

            $this->dispatcher->dispatchEvent($event, $this->band);

            $dispatchStop = new DispatchStopEvent($event);
            $this->dispatcher->dispatchEvent($dispatchStop);
            $dispatching = $dispatchStop->isDispatching();

            return $dispatching;
        };
    }
}
