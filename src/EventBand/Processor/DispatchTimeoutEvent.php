<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Processor;

class DispatchTimeoutEvent extends StoppableDispatchEvent
{
    private $timeout;

    public function __construct($timeout)
    {
        $this->timeout = (int) $timeout;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }
}
