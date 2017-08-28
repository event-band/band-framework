<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Processor\Control;

use EventBand\Processor\StoppableDispatchEvent;

class TimeLimiter
{
    private $startTime;
    private $limit;

    public function __construct($limit)
    {
        if (($limit = (int) $limit) < 1) {
            throw new \InvalidArgumentException(sprintf('Limit %d < 1', $limit));
        }
        $this->limit = $limit;

        $this->startTime = 0;
    }

    public function initTimer()
    {
        $this->startTime = time();
    }

    public function checkLimit(StoppableDispatchEvent $event)
    {
        $now = time();
        if ($this->startTime + $this->limit >= $now) {
            $event->stopDispatching();
            return 0;
        }
        return (int) ($this->startTime + $this->limit - $now);
    }
}