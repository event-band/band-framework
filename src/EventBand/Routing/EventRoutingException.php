<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Routing;

use EventBand\Event;

/**
 * Exception on event routing
 */
class EventRoutingException extends \RuntimeException
{
    private $event;

    public function __construct(Event $event, $reason, \Exception $previous = null)
    {
        $this->event = $event;

        parent::__construct(sprintf('Can not route event "%s": %s', $event->getName(), $reason), 0, $previous);
    }

    public function getEvent()
    {
        return $this->event;
    }
}
