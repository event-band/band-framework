<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Transport\Amqp\Driver;

use EventBand\Event;

interface MessageEventConverter
{
    /**
     * @param Event $event
     *
     * @return AmqpMessage
     */
    public function eventToMessage(Event $event);

    /**
     * @param AmqpMessage $message
     *
     * @return Event
     */
    public function messageToEvent(AmqpMessage $message);
}
