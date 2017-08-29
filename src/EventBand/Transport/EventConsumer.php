<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport;

/**
 * Extended event reader which can consume events from storage
 */
interface EventConsumer
{
    /**
     * Consume events and run event callback.
     * This method may be blocking but should return if no events exist in timeout seconds or by fixed timeout.
     * If callback fails event should be requeued.
     *
     * @param callable $callback    Event callback. If callback returns false consumption should be stopped.
     *                              Signature: bool function(Event $event)
     * @param int      $idleTimeout Idle timeout in seconds (>= 0).
     *                              If no events received in $timeout seconds consumer should stop consumption.
     * @param null     $timeout     Timeout in seconds (>= 0)
     *                              Timeout for full execution cycle. Consume stops when timeout reached.
     *
     * @return void                 On errors while event consumption
     */
    public function consumeEvents(callable $callback, $idleTimeout, $timeout = null);
}
