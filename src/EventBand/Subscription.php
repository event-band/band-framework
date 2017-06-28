<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand;

interface Subscription
{
    /**
     * Get subscribed event name
     *
     * @return string
     */
    public function getEventName();

    /**
     * Get name of band
     *
     * @return string
     */
    public function getBand();

    /**
     * Dispatch event
     *
     * @param Event          $event
     * @param BandDispatcher $dispatcher
     *
     * @return bool False to stop event propagation
     */
    public function dispatch(Event $event, BandDispatcher $dispatcher);
}
