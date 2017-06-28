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
 * Exception while publishing event
 */
class PublishEventException extends \RuntimeException
{
    private $event;

    /**
     * @param Event           $event    An event object
     * @param string          $reason   Error reason
     * @param \Exception|null $previous Previous exception
     */
    public function __construct(Event $event, $reason, \Exception $previous = null)
    {
        $this->event = $event;

        parent::__construct(sprintf('Can not publish event "%s": %s', $event->getName(), $reason), 0, $previous);
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
