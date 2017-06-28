<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand;

use EventBand\Transport\EventPublisher;

class PublishEventSubscriber
{
    private $publisher;
    private $propagation;

    /**
     * Constructor
     *
     * @param EventPublisher $publisher   Event publisher instance
     * @param bool           $propagation If false event propagation will be stopped after publish
     */
    public function __construct(EventPublisher $publisher, $propagation = true)
    {
        $this->publisher = $publisher;
        $this->propagation = (bool) $propagation;
    }

    public function __invoke(Event $event)
    {
        $this->publisher->publishEvent($event);

        return $this->propagation;
    }
}