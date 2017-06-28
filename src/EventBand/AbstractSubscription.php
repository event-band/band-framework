<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand;

abstract class AbstractSubscription implements Subscription
{
    private $eventName;
    private $band;

    public function __construct($eventName, $band = null)
    {
        $this->eventName = $eventName;
        $band = (string) $band;
        if (empty($band)) {
            $band = null;
        }
        $this->band = $band;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * {@inheritDoc}
     */
    public function getBand()
    {
        return $this->band;
    }
}