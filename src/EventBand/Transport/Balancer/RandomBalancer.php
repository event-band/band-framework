<?php

namespace EventBand\Transport\Balancer;;

use EventBand\Event;

/**
 * Class RandomBalancer
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 */
class RandomBalancer implements BalancingPolicy
{
    /**
     * {@inheritdoc}
     */
    public function getConnections(Event $event, array $connections)
    {
        $shuffled = [];
        $keys = array_keys($connections);
        shuffle($keys);
        foreach ($keys as $key) {
            $shuffled[$key] = $connections[$key];
        }

        return $shuffled;
    }
}
