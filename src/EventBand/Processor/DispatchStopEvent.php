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

class DispatchStopEvent extends StoppableDispatchEvent
{
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }
}