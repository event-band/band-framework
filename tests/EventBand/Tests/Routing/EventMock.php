<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Routing;

use EventBand\Event;

class EventMock implements Event
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mock';
    }

    public function getTime()
    {
        return new \DateTime();
    }
}
