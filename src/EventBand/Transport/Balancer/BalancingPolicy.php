<?php

namespace EventBand\Transport\Balancer;

use EventBand\Event;

/**
 * Class BalancingPolicy
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 */
interface BalancingPolicy
{
    /**
     * @param Event $event
     * @param array $connections Connections for balancing as [key => connection]
     *
     * @return array Ordered array of connections (keys are preserved)
     */
    public function getConnections(Event $event, array $connections);
}
