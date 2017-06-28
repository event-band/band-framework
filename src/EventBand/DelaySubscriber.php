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
 * Class DelaySubscriber
 */
class DelaySubscriber
{
    public function __invoke(DelayedEvent $event, BandDispatcher $dispatcher)
    {
        $timeToPublish = $event->getTime()->getTimestamp() + $event->getDelay() - time();
        if ($timeToPublish > 0) {
            sleep($timeToPublish);
        }

        $dispatcher->dispatchEvent($event->getOriginalEvent());
    }
}