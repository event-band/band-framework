<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand;

/**
 * Delayed event wraps original event which should be processed later
 */
interface DelayedEvent extends Event
{
    /**
     * Get original event
     *
     * @return Event
     */
    public function getOriginalEvent();

    /**
     * Get event time
     *
     * @return mixed
     */
    public function getTime();

    /**
     * Get delay
     *
     * @return int Delay in seconds
     */
    public function getDelay();
}
