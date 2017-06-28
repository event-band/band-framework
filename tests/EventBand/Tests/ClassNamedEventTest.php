<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests;

use EventBand\ClassNamedEvent;
use PHPUnit_Framework_TestCase as TestCase;

class ClassNamedEventTest extends TestCase
{
    /**
     * @test event name is base on class: dot-separated underscored lowercase name
     */
    public function classBasedName()
    {
        $this->assertEquals('event_band.tests.named_stub', NamedStubEvent::name());
    }
}
