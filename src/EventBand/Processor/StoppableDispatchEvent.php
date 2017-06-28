<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Processor;

use EventBand\ClassNamedEvent;

abstract class StoppableDispatchEvent extends ClassNamedEvent
{
    private $dispatching = true;

    public function isDispatching()
    {
        return $this->dispatching;
    }

    public function stopDispatching()
    {
        $this->dispatching = false;
    }
}
