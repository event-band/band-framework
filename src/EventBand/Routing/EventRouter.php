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
 * Router for events to publish them in a proper place
 */
interface EventRouter
{
    /**
     * Route event
     *
     * @param Event $event
     *
     * @return string
     * @throws EventRoutingException
     */
    public function routeEvent(Event $event);
}
