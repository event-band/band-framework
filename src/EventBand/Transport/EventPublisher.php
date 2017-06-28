<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport;


use EventBand\Event;

/**
 * Event publisher. Publishes events to custom storage for later processing
 */
interface EventPublisher
{
    /**
     * Publish event object
     *
     * @param Event $event
     *
     * @throws PublishEventException
     */
    public function publishEvent(Event $event);
}
